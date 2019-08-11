<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SalesOrder extends Model
{
    //
    // public $claimed_freight;
    // public $claimed_other_costs;
    public const DELIVERED           = 3;
    public const FULFILLED           = 2;
    public const PARTIALLY_FULFILLED = 1;

    public const INVOICED            = 2;
    public const PARTIALLY_INVOICED  = 1;

	public function Items() {
		return $this->hasMany('App\SalesOrderItemDetail', 'sales_order_id');
	}

    public function Fulfilments() {
        return $this->hasMany(Fulfilment::class, 'so_id');
    }

    public function Customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function CustomerName() {
        return Customer::getNameById($this->customer_id);
    }

    public function ClaimedOtherCosts() {
        if(!isset($this->claimed_other_costs)) {
            $this->CalculateClaimedFreightAndOtherCost();
        }
        return $this->claimed_other_costs;
    }

    public function ClaimedFreight() {
        if(!isset($this->claimed_other_costs)) {
            $this->CalculateClaimedFreightAndOtherCost();
        }
        return $this->claimed_freight;
    }

    public function CalculateClaimedFreightAndOtherCost() {
        $calc = Invoice::where('so_id', $this->id)->selectRaw('SUM(freight) AS claimed_freight, SUM(other_costs) AS claimed_other_costs')->first();
        $this->claimed_freight     = $calc->claimed_freight;
        $this->claimed_other_costs = $calc->claimed_other_costs;
        return $this;
    }

    public function isCompletelyFulfilled() {
        return !(SalesOrderItemDetail::where('sales_order_id', $this->id)
                ->where('balance_qty', '>', 0)
                ->exists());
    }

    public function FulfilmentsWithAmount($invoice_id = 0, $pending = true) {
        return Fulfilment::where('so_id', $this->id)
                    ->join('fulfilment_items', 'fulfilment_items.fulfilment_id', '=', 'fulfilments.id')
                    ->join('sales_order_item_details AS soid', 'soid.id', '=', 'fulfilment_items.so_item_id')
                    ->when($pending, function($q) use($invoice_id){
                        return $q->where('fulfilments.is_invoiced', 0)
                                ->when(($invoice_id > 0), function($q) use($invoice_id){
                                    return $q->orWhereExists(function($q) use($invoice_id){
                                        $q->selectRaw(1)
                                            ->from('invoice_lines')
                                            ->whereRaw('invoice_lines.fulfilment_id = fulfilments.id')
                                            ->where('invoice_lines.invoice_id', $invoice_id);
                                    });              
                                });
                    })
                    ->groupBy('fulfilments.id')
                    ->select('fulfilments.*', 
                        DB::Raw('ROUND(SUM(soid.item_rate * fulfilment_items.fulfilment_qty), 2) As fulfilment_amt'),
                        DB::Raw('ROUND(SUM(soid.item_rate * soid.tax_rate *  fulfilment_items.fulfilment_qty * 0.01 ), 2) As fulfilment_tax'))
                    ->get()->map(function($a){
                        $a->fulfilment_total = number_format($a->fulfilment_amt + $a->fulfilment_tax, 2);
                        return $a;
                    });
    }

    public function addItems(array $items) {
    	foreach ($items as $r_item) {
    		if(!empty($r_item['so_item_id']))
    			$item = SalesOrderItemDetail::find($r_item['so_item_id']);
    		else{
    			$item = new SalesOrderItemDetail();
                $item->balance_qty = $r_item['item_qty'];
            }

    		$item->item_id        = $r_item['item_id'];
    		$item->item_cost      = $r_item['item_cost'];
            $item->item_price     = $r_item['item_price'];
            $item->item_disc_per    = $r_item['item_disc_per'];
            $item->item_disc_amt = $r_item['item_disc_amt'];
            $item->item_rate        = $r_item['item_rate'];
            $item->tax_rate        = $r_item['tax_rate'] ?? 0;
    		$item->item_qty_on_hand = $r_item['qty_on_hand'] ?? 0;
            $item->item_qty         = $r_item['item_qty'];
            if(isset($r_item['balance_qty']))
    		  $item->balance_qty      = $r_item['balance_qty'];
    		$item->item_total = $r_item['item_total'];
    		$item->sales_order_id = $this->id;
    		$item->save();
    	}
    	return $item;
    }

    public function getItemsWithDetails() {
        return SalesOrderItemDetail::where('sales_order_id', $this->id)
                ->join('items', 'items.id', '=', 'sales_order_item_details.item_id')
                ->select('sales_order_item_details.*', 'items.item_name', 'items.qty_on_hand')
                ->get();
    }

    public function getStatus() {
        if($this->is_cancelled) {
            return "Cancelled";
        } else if($this->is_paid) {
            return "Paid and Closed";
        } else if($this->is_invoiced) {
            switch($this->is_invoiced) {
                case self::INVOICED:
                    return "Invoiced";
                case self::PARTIALLY_INVOICED:
                    return "Partially Invoiced";
            }
        } else {
            switch($this->fulfilment_status) {
                case self::DELIVERED:
                    return "Delivered";
                case self::FULFILLED:
                    return "Fulfilled";
                case self::PARTIALLY_FULFILLED:
                    return "Partially Fulfilled";
                default:
                    return "Order Entered";
            }
        }
    }

    public function setFulfilmentStatus($status) {
        $this->fulfilment_status = $status;
        return $this;
    }

    public function getFulfilmentStatus() {
        return $this->fulfilment_status;
    }

    public function setAsInvoiced() {
        if($this->fulfilment_status == 0) {
            return false;
        }
        if($this->fulfilment_status < self::FULFILLED) {
            $this->is_invoiced = self::PARTIALLY_INVOICED;
        } else {
            if(Fulfilment::where('so_id', $this->id)->where('is_invoiced', 0)->exists()) {
                $this->is_invoiced = self::PARTIALLY_INVOICED;
            } else {
                $this->is_invoiced = self::INVOICED;
            }
        }
        return $this->save();
    }

    // ************************  Static Functions ***************************
    public static function getStatuses() {
        return array(
                'ORDER_ENTERED'         => 'Order Entered', 
                'PARTIALLY_FULFILLED'   => 'Partially Fulfilled', 
                'FULFILLED'             => 'Fulfilled', 
                'PARTIALLY_INVOICED'    => 'Partially Invoiced', 
                'DELIVERED'             => 'Delivered', 
                'PAID'                  => 'Paid and Closed', 
                'CANCELLED'             => 'Cancelled', 
            );
    }

    public static function getMonthSales() {
        return self::where(DB::Raw(static::YearFromDateSQL('order_date')), date('Y'))
                    ->where(DB::Raw(static::MonthFromDateSQL('order_date')), date('m'))
                    ->sum('order_total') ?? 0;
    }

    public static function getYearSales() {
        return self::where(DB::Raw(static::YearFromDateSQL('order_date')), date('Y'))
                    ->sum('order_total') ?? 0;
    }

    public static function getPendingOrdersCount() {
        return self::where(DB::Raw('coalesce(is_cancelled, 0)'), 0)
                    ->where(DB::Raw('coalesce(fulfilment_status, 0)'), '<', 2)
                    ->count() ?? 0;
    }

    public static function YearFromDateSQL($column_name) {
        switch(DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME))
        {
            case 'pgsql':
                return "date_part('year', $column_name)";
                break;

            default:
                return "YEAR($column_name)";
                break;
        }
    }

    public static function MonthFromDateSQL($column_name) {
        switch(DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME))
        {
            case 'pgsql':
                return "date_part('month', $column_name)";
                break;

            default:
                return "MONTH($column_name)";
                break;
        }
    }

    
}
