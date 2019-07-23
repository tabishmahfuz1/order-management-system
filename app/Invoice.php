<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Invoice extends Model
{
    //
    public function saveInvoice(array $invoice) {
    	$invoiceOb = null;
    	DB::transaction(function() use($invoice, &$invoiceOb){
    		if(isset($invoice['invoice_id']))
    			$invoiceOb = self::find($invoice['invoice_id'])
    		else {
    			$invoiceOb = new self;
    		}
            
    	});
    	
    }
}
