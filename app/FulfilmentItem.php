<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FulfilmentItem extends Model
{
    //
    public function Fulfilment() {
    	return $this->belongsTo(Fulfilment::class);
    }

    public function SoItem() {
    	return $this->belongsTo(SalesOrderItemDetail::class);
    }

    public static function saveItem($item) {
    	$response = [];
    	if(isset($fulfilmentItem['fulfilment_item_id'])){
    		$fulfilmentItem = self::find($item['fulfilment_item_id']);
    		$response['old']= $fulfilmentItem->toArray();
    	}
    	else
    		$fulfilmentItem = new self;
    	
    	unset($item['fulfilment_item_id']);

    	foreach ($item as $key => $value) {
    		$fulfilmentItem[$key] = $value;
    	}
    	$fulfilmentItem->save();
    	$response['new']= $fulfilmentItem->toArray();
    	return $response;
    }
    
}
