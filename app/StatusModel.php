<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusModel extends Model
{
    //
    public $timestamps 	= false;

    static $statusIds;

    public static function getStatusIdByCode($code) {
    	if(isset($statusIds[$code]))
    		return $statusIds[$code];
    	return $statusIds[$code] = self::where('code', $code)->select('id')->first()->id ?? 0;
    }
}
