<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    private $state_name;
    public function Orders() {
    	return $this->hasMany(SalesOrder::class, 'customer_id');
    }
    public static function getNamebyId($id) {
    	return self::where('id', $id)->select('name')->first()->name ?? '';
    }

    public function stateName() {
    	return ($this->state_name 
    			?? ($this->state_name = StateModel::getStateNameById($this->state_id)));
    }
}
