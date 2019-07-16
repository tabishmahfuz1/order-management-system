<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SalesOrder;

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
    	dd($req->all());
    }

    public function editFulfilment($id) {
    	
    }
}
