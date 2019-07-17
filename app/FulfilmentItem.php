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

    public function setRemainingStock(int $qty) {
    	$this->remaining_stock = $qty;
    	return $this;
    }

    public static function saveItem($item) {
    	$response = [];
    	if(!empty($item['fulfilment_item_id'])){
    		$fulfilmentItem = self::find($item['fulfilment_item_id']);
    		$response['old']= $fulfilmentItem->replicate();
    		// dd($response);
    	}
    	else
    		$fulfilmentItem = new self;
    	
    	unset($item['fulfilment_item_id']);

    	foreach ($item as $key => $value) {
    		$fulfilmentItem[$key] = $value;
    	}
    	$fulfilmentItem->save();
    	$response['new']= $fulfilmentItem;
    	// dd($response);
    	return $response;
    }
    
}
