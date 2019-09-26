<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ItemType;

class ItemTypeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth')->except('itemTypes');
    }

    public function itemTypes(Request $req)
    {
        // if($req->expectsJson()) {
            return ItemType::all();
        // }
    }

    public function getItemType(ItemType $itemtype)
    {
        dd($itemtype);
    }

    public function saveItemType(ItemType $itemtype, Request $req)
    {
        // $itemType = $req->has('itemType.id')? ItemType::find($req->itemType['id'])
        //            : new ItemType();

        $itemtype->name = $req->itemType['name'];
        $itemtype->status = $req->has('itemType.status')? $req->itemType['status'] 
                            : true;
        $itemtype->save();
        return response()->json($itemtype);
    }

    public function listTypes(Request $req)
	{
		$itemTypes = ItemType::all();
    	return view('inventory.item-type.manage-item-types', compact('itemTypes'));
    }

    /*public function saveItemType(Request $req)
    {
    	dd($req->all());
    }*/
}
