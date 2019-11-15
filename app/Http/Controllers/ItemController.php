<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\ItemStockDetail;

class ItemController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newItem(Request $req) {
    	return view('inventory.new_item');
    }

    public function saveItem(Item $item, Request $req) {
    	if($item){
            // Already have the Item from Implicit Binding
        }
        else if(isset($req->item_id)){
    		$item = Item::find($req->item_id);
        }
    	else{
    		$item = new Item();
        }
        // dd($req->all());
    	$item->item_name   = $req->item_name;
    	$item->item_cost   = $req->item_cost;
        $item->item_price  = $req->item_price;
    	// $item->qty_on_hand = $req->qty_on_hand;
    	$item->status      = $req->status;
        $is_new_item       = ! $item->exists;
        if($is_new_item) {
            $item->qty_on_hand = 0;
        }
    	$item->save();

        if($is_new_item) {
            ItemStockDetail::saveItemStock([
                'type' => ItemStockDetail::OPENING_STOCK,
                'date' => date('Y-m-d'),
                'quantity' => $req->qty_on_hand,
                'remarks' => 'Opening Stock',
                'item_id' => $item->id
            ]);
        }

        if($req->expectsJson()) {
            return response()->json($item);
        }

    	return redirect()->route('edit_item', $item->id)->with(['success' => 'Information Saved']);
    }

    public function editItem($item_id) {
    	$item = Item::find($item_id);
    	return view('inventory.edit_item', compact('item'));
    }

    public function viewItems(Request $req) {
        // \DB::enableQueryLog();
    	$items = Item::orderBy('item_name', 'ASC')
                ->when(! empty($req->item_name), function($q){
                    return $q->where('item_name', 'LIKE', "%{$req->item_name}%");
                })
                ->when(! empty($req->item_cost), function($q) use($req){
                    $comparisonOperator = self::decodeSqlConversionOperator($req->item_cost_comparison);
                    if(! $comparisonOperator) {
                        return $q;
                    }
                    return $q->where('items.item_cost', $comparisonOperator, $req->item_cost);
                })
                ->when(! empty($req->item_price), function($q) use($req){
                    $comparisonOperator = self::decodeSqlConversionOperator($req->item_price_comparison);
                    if(! $comparisonOperator) {
                        return $q;
                    }
                    return $q->where('items.item_price', $comparisonOperator, $req->item_price);
                })
                ->when(! empty($req->qty_on_hand), function($q) use($req){
                    $comparisonOperator = self::decodeSqlConversionOperator($req->qty_on_hand_comparison);
                    if(! $comparisonOperator) {
                        return $q;
                    }
                    return $q->where('items.qty_on_hand', $comparisonOperator, $req->qty_on_hand);
                })
                ->when(isset($req->status), function($q) use($req){
                    $q->where('item.status', '=', $req->status);
                })
                ->get();
                // return \DB::getQueryLog();
    	return view('inventory.view_items', compact('items'));
    }

    public function addStockDetail(Request $req) {
        // dd($req->all());
        // $item = Item::find($req->item_id);
        // $item->addStockDetail($req->all());
        $itemStockDetail = ItemStockDetail::saveItemStock($req->all());
        return $itemStockDetail;
    }

    public function saveItemStockDetail($itemId, ItemStockDetail $itemstockdetail, Request $req)
    {
       return $itemStockDetail = ItemStockDetail::saveItemStock($req->all(), $itemstockdetail);
    }

    /**
    * Get Item by item_id
    *
    * @param int $item_id
    * @return Item
    */
    public function getItem($item_id) {
        return Item::find($item_id);
    }

    public function getItemStockDetails(Item $item)
    {
        if(!($item->exists ?? false)) {
            return response()->json("Item Not found", 404);
        }
        return response()->json($item->StockDetails);
    }
}
