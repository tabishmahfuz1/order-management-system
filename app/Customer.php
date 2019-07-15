<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    public function Orders() {
    	return $this->hasMany(SalesOrder::class, 'customer_id');
    }
    public static function getNamebyId($id) {
    	return self::where('id', $id)->select('name')->first()->name ?? '';
    }
}
