<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    //

    public function listTypes(Request $req)
	{
		
    	return view('inventory.item-type.manage-item-types');
    }
}
