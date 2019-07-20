<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StateModel extends Model
{
    //
	private $stateNames;
    public static function getStateNameById($state_id) {
    	return $stateNames[$state_id] 
    			?? (self::where('id', $state_id)->select('name')->first()->name ?? '');
    }
}
