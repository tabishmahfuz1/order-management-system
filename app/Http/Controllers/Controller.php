<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function decodeSqlConversionOperator($operatorCode) {
    	switch ($operatorCode) {
    		case 'lt':
    			return '<';
    		case 'le':
    			return '<=';
    		case 'ge':
    			return '>=';
    		case 'gt':
    			return '>';
    		case 'eq':
    			return '=';
    		default:
    			return false;
    	}
    }
}
