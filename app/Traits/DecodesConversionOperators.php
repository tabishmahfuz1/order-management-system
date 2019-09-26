<?php
namespace App\Traits;

trait DecodesConversionOperators {
	public function decodeSqlConversionOperator($operatorCode) {
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