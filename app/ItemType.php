<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    //
    public function Items()
    {
    	return $this->hasMany(Item::class, 'item_type_id');
    }
}
