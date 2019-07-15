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

    public function newFulfilment(Request $req) {
    	$orders = SalesOrder::select('id', 'sales_order_no')->get();
    	return view('fulfilment.new_fulfilment', compact('orders'));
    }

}
