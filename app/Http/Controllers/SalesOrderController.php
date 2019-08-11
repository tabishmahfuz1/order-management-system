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
        // \DB::enableQueryLog();
    	$orders = SalesOrder::orderBy('id', 'DESC')
                    ->join('customers', 'customers.id', '=', 'sales_orders.customer_id')
                    ->select('sales_orders.*', 'customers.name As customer')
                    ->when(! empty($req->sales_order_no), function($q) use($req){
                        return $q->where('sales_order_no', 'LIKE', "%{$req->sales_order_no}%");
                    })
                    ->when(! empty($req->customer), function($q) use($req){
                        return $q->where('customers.name', 'LIKE', "%{$req->customer}%");
                    })
                    ->when(! empty($req->ref_no), function($q) use($req){
                        return $q->where('ref_no', 'LIKE', "%{$req->ref_no}%");
                    })
                    ->when(! empty($req->order_date), function($q) use($req){
                        $comparisonOperator = self::decodeSqlConversionOperator($req->order_date_comparison);
                        if(! $comparisonOperator) {
                            return $q;
                        }
                        
                        return $q->where('order_date', $comparisonOperator, $req->order_date);
                    })
                    ->when(! empty($req->order_total), function($q) use($req){
                        $comparisonOperator = self::decodeSqlConversionOperator($req->order_total_comparison);
                        if(! $comparisonOperator) {
                            return $q;
                        }
                        
                        return $q->where('order_total', $comparisonOperator, $req->order_total);
                    })
                    ->get();
        // return $req->all();
        $view = view('order.view_sales_orders', compact('orders'));
        if($req->ajax()) {
            $sections = $view->renderSections(); // returns an associative array of 'content', 'head' and 'footer'

            return $sections['content']; // this will only return whats in the content section

        }
    	return $view;
    }

    public function editOrder($order_id) {
    	$order = SalesOrder::find($order_id);
    	$so_items = SalesOrderItemDetail::getOrderItems($order_id);
    	$items = Item::where('status', 1)->select('id', 'item_name')->get();
    	$customers = Customer::select('id', 'name', 'discount')->get();
        // $statuses = SalesOrder::getStatuses();
    	return view('order.edit_sales_order', compact('order', 'so_items', 'items', 'customers'/*, 'statuses'*/));
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
        $order->memo            = $req_order['memo'];
    	// $order->status 			= $req_order['status'];
    	$order->save();
    	$order->sales_order_no 	= 'SO-'.str_pad($order->id, 7, "0", STR_PAD_LEFT);
    	$order->save();

        $order->addItems($req_order['items']);

    	return redirect()->route('edit_sales_order', $order->id)->with(['success' => 'Information Saved']);
    }

    public function addSoItem(Request $req) {
        // dd($req->all());
        $order = SalesOrder::find($req->so_id);
        $item  = $order->addItems([$req->toArray()]);
        $item->item_name = $req->item_name;
        return response()->json(array('success' => true, 'row' => view('order.so_item_row', compact('item'))->render()));
    }

    public function getOrderDetail($order_id) {
        $order = SalesOrder::find($order_id);
        $order->customer_name = Customer::getNameById($order->customer_id);
        $order->Items = $order->getItemsWithDetails();
        return response()->json($order);
    }

    public function getOrderListForDataTable(Request $req) {
         $columns = array( 
                            0 =>'sales_orders.sales_order_no', 
                            1 =>'sales_orders.order_date',
                            2=> 'customers.name',
                            3=> 'sales_orders.ref_no',
                            4=> 'sales_orders.order_total',
                            4=> 'sales_orders.status',
                        );
  
        $totalData = Post::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $posts = Post::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $posts =  Post::where('id','LIKE',"%{$search}%")
                            ->orWhere('title', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Post::where('id','LIKE',"%{$search}%")
                             ->orWhere('title', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $show =  route('posts.show',$post->id);
                $edit =  route('posts.edit',$post->id);

                $nestedData['id'] = $post->id;
                $nestedData['title'] = $post->title;
                $nestedData['body'] = substr(strip_tags($post->body),0,50)."...";
                $nestedData['created_at'] = date('j M Y h:i a',strtotime($post->created_at));
                $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                                          &emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 
    }
}
