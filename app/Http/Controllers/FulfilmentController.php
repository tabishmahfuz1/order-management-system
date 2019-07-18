<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SalesOrder;
use App\Fulfilment;

class FulfilmentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newFulfilment($order_id = 0) {
    	$orders = SalesOrder::select('id', 'sales_order_no')->get();
    	return view('fulfilment.new_fulfilment', compact('orders', 'order_id'));
    }

    public function saveFulfilment(Request $req) {
    	// dd($req->all());
        $fulfilment = Fulfilment::saveFulfilment($req->fulfilment);
        return redirect()->route('edit_fulfilment', ['order_id' => $fulfilment->id]);
    }

    public function editFulfilment($id) {
    	$fulfilment = Fulfilment::find($id);
        return view('fulfilment.edit_fulfilment', compact('fulfilment'));
    }

    public function viewFulfilments(Request $req) {
        $fulfilments = Fulfilment::join('sales_orders', 'sales_orders.id', '=', 'fulfilments.so_id')
                                    ->join('customers', 'customers.id', '=', 'sales_orders.customer_id')
                                    ->select('fulfilments.*', 'sales_orders.sales_order_no', 'sales_orders.ref_no', 'sales_orders.order_date', 'customers.name AS customer_name')
                                    ->get();
        return view('fulfilment.view_fulfilments', compact('fulfilments'));
    }
}
