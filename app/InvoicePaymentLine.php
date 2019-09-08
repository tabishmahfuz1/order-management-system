<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class InvoicePaymentLine extends Model
{
    //
    public function Invoice() {
    	return $this->belongsTo(Invoice::class, 'invoice_id');
    }



    /**
    *
    * Create or Update an Invoice Payment Line
    *
    * @param $paymentDetailsArr array Array containing Payment Information
    * @return App\InvoicePaymentLine The saved payment line object
    *
    */
    public static function savePaymentLine(array $paymentDetailsArr) {
    	$paymentLine = null;

    	DB::transaction(function() use(&$paymentLine, $paymentDetailsArr){
    		$paymentLine = self::find($paymentDetailsArr['payment_line_id']) ?? new self;

    		if($paymentLine->exists) {
    			$invoice = $paymentLine->Invoice;
    			$invoice->setBalanceAmount($invoice->getBalanceAmount() + $paymentLine->received_amt);
    		} else {
    			$invoice = Invoice::findOrFail($paymentDetailsArr['invoice_id']);
    			$paymentLine->invoice_id = $invoice->id;
    			$paymentLine->line_num   = self::where('invoice_id', $invoice->id)->count() + 1;
    		}

	    	$paymentLine->received_amt 	= $paymentDetailsArr['received_amt'];
	    	$paymentLine->date_received = $paymentDetailsArr['date_received'];
	    	$paymentLine->balance_amt 	= $invoice->getBalanceAmount() - $paymentLine->received_amt;

	    	$paymentLine->save();

	    	$invoice->setBalanceAmount($paymentLine->balance_amt)->setPaymentStatus()->save();

    	});
    	return $paymentLine;
    	
    }
}
