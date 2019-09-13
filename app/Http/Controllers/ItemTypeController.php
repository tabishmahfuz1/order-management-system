<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ItemType;

class ItemTypeController extends Controller
{
    //

    public function listTypes(Request $req)
	{
		$itemTypes = ItemType::all();
    	return view('inventory.item-type.manage-item-types', compact('itemTypes'));
    }
}
