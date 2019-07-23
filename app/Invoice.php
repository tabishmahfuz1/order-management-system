<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Invoice extends Model
{
    //
    public function Fulfilments() {
    	return $this->hasMany(InvoiceLine::class, 'invoice_id');
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
    					->setFreight($invoice['freight'])
    					->setOtherCosts($invoice['other_costs'])
    					->CalculateGrandTotal()
    					->save();

    		$invoiceOb->setFulfilments($invoice['fulfilments'])
    				->CalculateGrandTotal()
    				->save();

    		if(empty($invoice['invoice_id'])){
    			$invoiceOb->setBalanceAmount($invoiceOb->grandtotal)
    						->GenerateInvoiceNumber()->save();
    		}
    	});
    	return $invoiceOb;	
    }
}
