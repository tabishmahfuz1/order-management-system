<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    //
    public function Invoice() {
    	return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public static function saveInvoiceLine(array $invoiceLine) {
    	$invLine = new self;
    	$invLine->fulfilment_id	= $invoiceLine['fulfilment_id'];
    	$invLine->fulfilment_amt= $invoiceLine['fulfilment_amt'];
    	$invLine->tax_amt 		= $invoiceLine['tax_amt'];
    	$invLine->invoice_id 	= $invoiceLine['invoice_id'];
		$invLine->save();
		return $invLine;
    }
}
