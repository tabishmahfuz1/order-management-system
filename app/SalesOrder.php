<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SalesOrder extends Model
{
    //

    public const DELIVERED           = 3;
    public const FULFILLED           = 2;
    public const PARTIALLY_FULFILLED = 1;

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

    public function isCompletelyFulfilled() {
        return !(SalesOrderItemDetail::where('sales_order_id', $this->id)
                ->where('balance_qty', '>', 0)
                ->exists());
    }

    public function FulfilmentsWithAmount() {
        return Fulfilment::where('so_id', $this->id)
                    ->join('fulfilment_items', 'fulfilment_items.fulfilment_id', '=', 'fulfilments.id')
                    ->join('sales_order_item_details', 'sales_order_item_details.id', '=', 'fulfilment_items.so_item_id')
                    ->groupBy('fulfilments.id')
                    ->select('fulfilments.*', DB::Raw('SUM(sales_order_item_details.item_rate * fulfilment_items.fulfilment_qty) As fulfilment_amt'))
                    ->get();
    }

    public function addItems(array $items) {
    	foreach ($items as $r_item) {
    		if(isset($r_item['so_item_id']))
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
            $item->tax_rate        = $r_item['tax_rate'];
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
            return "Invoiced";
        } else {
            switch($this->fulfilment_status) {
                case 3:
                    return "Delivered";
                case 2:
                    return "Fulfilled";
                case 1:
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

    // ************************  Static Functions ***************************
    public static function getStatuses() {
        return StatusModel::where('module', 'SO')->get();
    }

    public static function getMonthSales() {
        return self::where(DB::Raw('YEAR(order_date)'), date('Y'))
                    ->where(DB::Raw('MONTH(order_date)'), date('m'))
                    ->sum('order_total') ?? 0;
    }

    public static function getYearSales() {
        return self::where(DB::Raw('YEAR(order_date)'), date('Y'))
                    ->sum('order_total') ?? 0;
    }

    public static function getPendingOrdersCount() {
        return self::where(DB::Raw('IFNULL(is_cancelled, 0)'), 0)
                    ->where(DB::Raw('IFNULL(fulfilment_status, 0)'), '<', 2)
                    ->count() ?? 0;
    }
}
