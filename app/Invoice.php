<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Invoice extends Model
{
    public const PAID           = 2;
    public const PARTIALLY_PAID = 1;
    public const NOT_PAID = 0;
    //
    public function Fulfilments() {
    	return $this->hasMany(InvoiceLine::class, 'invoice_id');
    }

    public function Payments() {
        return $this->hasMany(InvoicePaymentLine::class, 'invoice_id');
    }

    public function getFulfilmentIds() {
        return InvoiceLine::where('invoice_id', $this->id)->select('fulfilment_id')->get()
                ->map(function($i){
                    return $i->fulfilment_id;
                });
    }

    public function Order() {
        return $this->belongsTo(SalesOrder::class, 'so_id');
    }

    public function CalculateGrandTotal() {
    	$this->grandtotal 	= $this->subtotal + $this->tax_amt + $this->freight + $this->other_costs;
    	return $this;
    }

    public function setFreight(float $freight) {
    	$this->freight = $freight;
    	return $this;
    }

    public function setOtherCosts(float $other_costs) {
    	$this->other_costs = $other_costs;
    	return $this;
    }

    public function setInvoiceDate($date) {
    	$this->invoice_date = $date;
    	return $this;
    }

    public function setSalesOrderID(int $so_id) {
    	$this->so_id = $so_id;
    	return $this;
    }

    public function setCustomerID(int $customer_id) {
    	$this->customer_id = $customer_id;
    	return $this;
    }

    public function setBalanceAmount(float $balance_amt) {
    	$this->balance_amt = $balance_amt;
        $this->received_amt = $this->grandtotal - $this->balance_amt;
    	return $this;
    }

    public function setFulfilments(array $fulfilments) {
    	if(!isset($this->id)) {
    		throw new \Exception("Can add Fulfilments to unsaved Invoice", 1110);
    	}
    	$id_count = count($fulfilments);
    	$fulfilments = Fulfilment::whereIn('id', $fulfilments)->get();
    	if(count($fulfilments) < $id_count) {
    		throw new \Exception("One or more of the Fulfilment IDs are invalid", 1111);
    	}

    	$this->subtotal = 0;
    	$this->tax_amt 	= 0;
    	InvoiceLine::where('invoice_id', $this->id)->delete();
    	foreach ($fulfilments as $fulfilment) {
    		$line = array(
    						'fulfilment_id' 	=> $fulfilment->id, 
    						'fulfilment_amt' 	=> $fulfilment->getFulfilmentAmount(),
							'tax_amt' 			=> $fulfilment->getFulfilmentTax(),
    						'invoice_id' 		=> $this->id
    					);
    		$this->subtotal += $line['fulfilment_amt'];
    		$this->tax_amt 	+= $line['tax_amt'];
    		InvoiceLine::saveInvoiceLine($line);
    		$fulfilment->setAsInvoiced();
    	}

    	$this->save();

    	return $this;
    }

    public function GenerateInvoiceNumber() {
    	$this->invoice_no = 'INV-'.str_pad($this->id, 6, "0", STR_PAD_LEFT);
    	return $this;
    }

    public function getInvoiceTotal() {
        return $this->grandtotal;
    }

    public function getBalanceAmount() {
        return $this->balance_amt;
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
        $calc = Invoice::where('so_id', $this->id)->where('id', '!=', $this->id)
                    ->selectRaw('SUM(freight) AS claimed_freight, SUM(other_costs) AS claimed_other_costs')->first();
        $this->claimed_freight     = $calc->claimed_freight ?? 0;
        $this->claimed_other_costs = $calc->claimed_other_costs ?? 0;
        return $this;
    }

    public function getReceivedAmount() {
        return $this->received_amt ?? 0;
    }

    public function CalculateBalanceAmount() {
        $this->balance_amt = $this->getInvoiceTotal() - $this->getReceivedAmount();
        return $this;
    }

    public function isPaid() {
        return $this->is_paid;
    }

    public function setPaymentStatus($status = false) {
        if($status !== false) {
            if($status != self::NOT_PAID and $status != self::PAID and $status != self::PARTIALLY_PAID)
                throw new \Exception("Invalid Payment Status given", 1201);
                
            $this->is_paid = $status;
        } else {
            if(!$this->received_amt) {
                $this->is_paid = self::NOT_PAID;
            } else if($this->is_paid < $this->grandtotal) {
                $this->is_paid = self::PARTIALLY_PAID;
            } else {
                $this->is_paid = self::PAID;
            }
        }
        return $this;
    }

    public static function saveInvoice(array $invoice) {
    	$invoiceOb = null;
    	DB::transaction(function() use($invoice, &$invoiceOb){
    		if(isset($invoice['invoice_id']))
    			$invoiceOb = self::find($invoice['invoice_id']);
    		else {
    			$invoiceOb = new self;
    			$invoiceOb->invoice_no = 'TEMP';
    		}
    		$invoiceOb->setSalesOrderID($invoice['so_id'])
    					->setInvoiceDate($invoice['invoice_date'])
    					->setCustomerID($invoice['customer_id'])
    					->setFreight($invoice['freight'] ?? 0)
    					->setOtherCosts($invoice['other_costs'] ?? 0)
    					->CalculateGrandTotal()
    					->save();

    		$invoiceOb->setFulfilments($invoice['fulfilments'])
    				->CalculateGrandTotal()
                    ->calculateBalanceAmount()
    				->save();

            $invoiceOb->Order->setAsInvoiced();

    		if(empty($invoice['invoice_id'])){
    			$invoiceOb->GenerateInvoiceNumber()->save();
    		}
    	});
    	return $invoiceOb;	
    }
}
