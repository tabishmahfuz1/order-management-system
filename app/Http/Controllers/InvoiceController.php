<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SalesOrder;
use App\Customer;
use App\Invoice;

class InvoiceController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newInvoice($order_id = 0) {
    	$orders = SalesOrder::where('is_invoiced', '<', 2)
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
                        ->get();
        return view('invoice.view_invoices', compact('invoices'));
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
}
