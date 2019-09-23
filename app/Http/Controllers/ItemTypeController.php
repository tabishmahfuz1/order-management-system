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

    public function saveItemType(Request $req)
    {
        $itemType = $req->has('itemType.id')? ItemType::find($req->itemType['id'])
                    : new ItemType();

        $itemType->name = $req->itemType['name'];
        $itemType->status = $req->has('itemType.status')? $req->itemType['status'] 
                            : true;
        $itemType->save();
        return response()->json($itemType);
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
