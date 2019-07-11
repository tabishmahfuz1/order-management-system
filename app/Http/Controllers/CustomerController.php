<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newCustomer(Request $req) {
    	# code...
    	$states = \App\StateModel::select('id', 'name')->get();
    	return view('customer.new_customer', compact('states'));
    }

    public function editCustomer($customer_id) {
    	# code...
    	$customer = Customer::find($customer_id);
    	$states = \App\StateModel::select('id', 'name')->get();
    	return view('customer.edit_customer', compact('customer', 'states'));
    }

    public function saveCustomer(Request $req) {
    	# code...
    	if(isset($req->customer_id))
    		$customer = Customer::find($req->customer_id);
    	else
    		$customer = new Customer();
		$customer->name = $req->name;
		$customer->city = $req->city;
		$customer->address = $req->address;
		$customer->state_id = $req->state;
		$customer->discount = $req->discount;
    	$customer->save();

    	return redirect()->route('edit_customer', ['customer_id' => $customer->id])->with(['success' => 'Information Saved']);
    }

    public function viewCustomers(Request $req) {
    	$customers = Customer::orderBy('id', 'DESC')->get();
    	return view('customer.view_customers', compact('customers'));
    }
}
