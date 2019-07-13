<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesOrder;
use App\SalesOrderItemDetail;
use App\Customer;
use App\Item;

class SalesOrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newSalesOrder(Request $req) {
    	$customers 	= Customer::select('id', 'name', 'discount')->get();
    	$items 		= Item::select('id', 'item_name')->get();
    	return view('order.new_sales_order', compact('customers', 'items'));
    }

    public function viewSalesOrders(Request $req) {
    	$orders = SalesOrder::orderBy('id', 'DESC')->join('customers', 'customers.id', '=', 'sales_orders.customer_id')->select('sales_orders.*', 'customers.name As customer')->get();
    	return view('order.view_sales_orders', compact('orders'));
    }

    public function editOrder($order_id) {
    	$order = SalesOrder::find($order_id);
    	$so_items = SalesOrderItemDetail::getOrderItems($order_id);
    	$items = Item::where('status', 1)->select('id', 'item_name')->get();
    	$customers = Customer::select('id', 'name', 'discount')->get();
    	return view('order.edit_sales_order', compact('order', 'so_items', 'items', 'customers'));
    }

    public function saveOrder(Request $req) {
    	// echo "<pre>"; print_r($req->all()); die;
    	$req_order = ($req->order);
    	if(isset($req->order_id))
    		$order = SalesOrder::find($req->order_id);
    	else
    		$order = new SalesOrder();

    	$order->customer_id 	= $req_order['customer_id'];
        $order->order_date      = date('Y-m-d', strtotime($req_order['order_date']));
        $order->sales_order_no  = 'TEMP';
    	$order->ref_no 	        = $req_order['ref_no'];
    	$order->sub_total 		= $req_order['sub_total'];
    	$order->discount_percent 	= ($req_order['discount_percent'] ?? 0);
    	$order->discount_amount 	= ($req_order['discount_amount'] ?? 0);
    	$order->tax_percent 	= ($req_order['tax_percent'] ?? 0);
    	$order->tax_amount 		= ($req_order['tax_amount'] ?? 0);
    	$order->freight 		= ($req_order['freight'] ?? 0);
    	$order->other_costs 	= ($req_order['other_costs'] ?? 0);
    	$order->order_total 	= ($req_order['order_total'] ?? 0);
    	$order->memo 			= $req_order['memo'];
    	$order->save();
    	$order->sales_order_no 	= 'SO-'.str_pad($order->id, 7);
    	$order->save();

        $order->addItems($req_order['items']);

    	return redirect()->route('edit_sales_order', $order->id)->with(['success' => 'Information Saved']);
    }

    public function addSoItem(Request $req) {
        // dd($req->all());
        $order = SalesOrder::find($req->so_id);
        $item  = $order->addItems([$req->toArray()], true);
        return response()->json(array('row' => view('order.so_item_row', compact('item'))->render()));
    }
}
