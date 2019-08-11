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
    	$customers = Customer::leftJoin('state_models', 'state_models.id', '=', 'customers.state_id')
                    ->select('customers.*', 'state_models.name AS state')
                    ->when(! empty($req->name), function($q) use($req){
                        return $q->where('customers.name', 'LIKE', "%{$req->name}%");
                    })
                    ->when(! empty($req->address), function($q) use($req){
                        return $q->where('customers.address', 'LIKE', "%{$req->address}%");
                    })
                    ->when(! empty($req->city), function($q) use($req){
                        return $q->where('customers.city', 'LIKE', "%{$req->city}%");
                    })
                    ->when(! empty($req->state), function($q) use($req){
                        return $q->where('state_models.name', 'LIKE', "%{$req->state}%");
                    })
                    ->when(! empty($req->discount), function($q) use($req){
                        $comparisonOperator = self::decodeSqlConversionOperator($req->discount_comparison);

                        if(! $comparisonOperator) {
                            return $q;
                        }
                        
                        return $q->where('customers.discount', $comparisonOperator, $req->discount);
                    })
                    ->when(isset($req->status), function($q) use($req){
                        return $q->where('customers.status', '=', $req->status);
                    })
                    ->orderBy('id', 'DESC')->get();
    	return view('customer.view_customers', compact('customers'));
    }
}
