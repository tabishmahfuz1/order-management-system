<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SalesOrder;
use App\Customer;
use App\Invoice;
use App\InvoicePaymentLine;

class InvoiceController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newInvoice($order_id = 0) {
    	$orders = SalesOrder::where('is_invoiced', '<', SalesOrder::INVOICED)
    						->where('fulfilment_status', '>', 0)
    						->select('id', 'sales_order_no')
    						->get();
    	return view('invoice.new_invoice', compact('orders'));
    }

    public function saveInvoice(Request $req) {
    	// dd($req->all());
    	$inv = Invoice::saveInvoice($req->invoice);
    	return redirect()->route('edit_invoice', ['invoice_id' => $inv->id]);
    }

    public function editInvoice($invoice_id) {
    	$invoice = Invoice::find($invoice_id)->CalculateClaimedFreightAndOtherCost();
    	return view('invoice.edit_invoice', compact('invoice'));
    }

    public function viewInvoices(Request $req) {
        $invoices = Invoice::join('sales_orders', 'sales_orders.id', '=', 'invoices.so_id')
                        ->join('customers', 'customers.id', '=', 'invoices.customer_id')
                        ->select('invoices.*', 'customers.name AS customer_name', 'sales_orders.sales_order_no', 'sales_orders.order_date')
                        ->when(! empty($req->invoice_no), function($q) use($req){
                            return $q->where('invoice_no', 'LIKE', "%{$req->invoice_no}%");
                        })
                        ->when(! empty($req->sales_order_no), function($q) use($req){
                            return $q->where('sales_orders.sales_order_no', 'LIKE', "%{$req->sales_order_no}%");
                        })
                        ->when(! empty($req->customer_name), function($q) use($req){
                            return $q->where('customers.name', 'LIKE', "%{$req->customer_name}%");
                        })
                        ->when(! empty($req->order_date), function($q) use($req){
                            $comparisonOperator = self::decodeSqlConversionOperator($req->order_date_comparison);
                            if(! $comparisonOperator) {
                                return $q;
                            }
                            
                            return $q->where('sales_orders.order_date', $comparisonOperator, $req->order_date);
                        })
                        ->when(isset($req->order_total), function($q) use($req){
                            $comparisonOperator = self::decodeSqlConversionOperator($req->order_total_comparison);
                            if(! $comparisonOperator) {
                                return $q;
                            }
                            
                            return $q->where('sales_orders.order_total', $comparisonOperator, $req->order_total);
                        })
                        ->when(isset($req->invoice_total), function($q) use($req){
                            $comparisonOperator = self::decodeSqlConversionOperator($req->invoice_total_comparison);
                            if(! $comparisonOperator) {
                                return $q;
                            }
                            
                            return $q->where('grandtotal', $comparisonOperator, $req->invoice_total);
                        })
                        ->when(isset($req->order_total), function($q) use($req){
                            $comparisonOperator = self::decodeSqlConversionOperator($req->order_total_comparison);
                            if(! $comparisonOperator) {
                                return $q;
                            }
                            
                            return $q->where('sales_orders.order_total', $comparisonOperator, $req->order_total);
                        })
                        ->when(isset($req->balance_amt), function($q) use($req){
                            $comparisonOperator = self::decodeSqlConversionOperator($req->balance_amt_comparison);
                            if(! $comparisonOperator) {
                                return $q;
                            }
                            
                            return $q->where('balance_amt', $comparisonOperator, $req->balance_amt);
                        })
                        ->get();
        $view = view('invoice.view_invoices', compact('invoices'));
        if($req->ajax()) {
            $sections = $view->renderSections(); // returns an associative array of 'content', 'head' and 'footer'

            return $sections['content']; // this will only return whats in the content section

        }
        return $view;
    }

    public function getOrderDetails($order_id, $invoice_id = 0) {
    	$order = SalesOrder::find($order_id);
    	$order->customer_name 	= Customer::getNameById($order->customer_id);
        $order->Fulfilments 	= $order->FulfilmentsWithAmount($invoice_id);
        $order->claimed_freight = $order->ClaimedFreight();
        $order->claimed_other_costs = $order->ClaimedOtherCosts();
        return response()->json($order);
    }

    public function getFulfilmentItems($fulfilment_id) {
    	$items = \App\FulfilmentItem::where('fulfilment_id', $fulfilment_id)
    				->join('sales_order_item_details AS soid', 'soid.id', '=', 'fulfilment_items.so_item_id')
    				->join('items', 'items.id', '=', 'soid.item_id')
    				->select('fulfilment_items.*', 'soid.item_price', 'soid.item_disc_amt', 'soid.item_rate', 'soid.tax_rate', 'items.item_name')
    				->get();
    	return response()->json($items);
    }

    public function addPayment(Request $req) {
        $paymentLine = InvoicePaymentLine::savePaymentLine($req->all());
        return view('invoice.payment_row', compact('paymentLine'));
    }
}
