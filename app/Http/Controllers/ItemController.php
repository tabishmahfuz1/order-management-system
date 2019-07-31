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

    public function saveItem(Request $req) {
    	if(isset($req->item_id))
    		$item = Item::find($req->item_id);
    	else
    		$item = new Item();

    	$item->item_name   = $req->item_name;
    	$item->item_cost   = $req->item_cost;
        $item->item_price  = $req->item_price;
    	$item->qty_on_hand = $req->qty_on_hand;
    	$item->status      = $req->status;
    	$item->save();

    	return redirect()->route('edit_item', $item->id)->with(['success' => 'Information Saved']);
    }

    public function editItem($item_id) {
    	$item = Item::find($item_id);
    	return view('inventory.edit_item', compact('item'));
    }

    public function viewItems(Request $req) {
    	$items = Item::orderBy('item_name', 'ASC')->get();
    	return view('inventory.view_items', compact('items'));
    }

    public function addStockDetail(Request $req) {
        // dd($req->all());
        // $item = Item::find($req->item_id);
        // $item->addStockDetail($req->all());
        $itemStockDetail = ItemStockDetail::saveItemStock($req->all());
        return $itemStockDetail;
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
}
