<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB; 
class Fulfilment extends Model
{
    //
    public function Items() {
    	return $this->hasMany(FulfilmentItem::class);
    }

    public function Order() {
    	return $this->belongsTo(SalesOrder::class, 'so_id');
    }

    public function FullItemDetails() {
    	return FulfilmentItem::join('sales_order_item_details', 'sales_order_item_details.id', '=', 'fulfilment_items.so_item_id')
    					->where('fulfilment_items.fulfilment_id', $this->id)
    					->join('items', 'items.id', '=', 'fulfilment_items.item_id')
    					->select('fulfilment_items.*', 'sales_order_item_details.item_qty', 'sales_order_item_details.item_rate', 'items.item_name', DB::Raw('(items.qty_on_hand + fulfilment_items.fulfilment_qty) AS qty_on_hand'))
    					->get();
    }

    public static function saveFulfilment($fulfilment) {
    	// dd($fulfilment);
    	$fulfilmentOb = null;
    	DB::transaction(function() use($fulfilment, &$fulfilmentOb){
    		if(isset($fulfilment['fulfilment_id'])){
	    		$fulfilmentOb = self::find($fulfilment['fulfilment_id']);
	    		unset($fulfilment['fulfilment_id']);
	    	}
	    	else
	    		$fulfilmentOb = new self;

	    	if(isset($fulfilment['items'])){
	    		$items = $fulfilment['items'];
	    		unset($fulfilment['items']);
	    	}
	    	
	    	foreach ($fulfilment as $key => $value) {
	    		$fulfilmentOb[$key] = $value;
	    	}
	    	$fulfilmentOb->fulfilment_no = 'TEMP';
	    	$fulfilmentOb->save();
	    	$fulfilmentOb->fulfilment_no = 'FMT-'.str_pad($fulfilmentOb->id, 6, "0", STR_PAD_LEFT);
	    	$fulfilmentOb->save();

	    	if($items)
	    		$fulfilmentOb->setItems($items);

	        $fulfilmentOb->updateOrderStatus();

    	});
	    return $fulfilmentOb;

    }

    public function updateOrderStatus() {
        if($this->Order->isCompletelyFulfilled()) {
            $this->Order->setStatus(SalesOrder::FULFILLED)
                        ->save();
        } else {
            $this->Order->setStatus(SalesOrder::PARTIALLY_FULFILLED)
                        ->save();
        }
    }

    public function setItems($items) {
    	foreach ($items as $key => $item) {
    		$so_item 					= SalesOrderItemDetail::find($item['so_item_id']);
    		$item['item_id'] 			= $so_item->item_id;
    		$item['balance_qty'] 		= $so_item->balance_qty;
    		$item['remaining_qty'] 		= $so_item->balance_qty - $item['fulfilment_qty'];
    		$item['remaining_stock'] 	= $so_item->Item->qty_on_hand;
    		$item['fulfilment_id'] 		= $this->id;
    		$fulfilment_item 			= FulfilmentItem::saveItem($item);

    		if(!empty($fulfilment_item['old']))
    			$so_item->balance_qty 		+= $fulfilment_item['old']['fulfilment_qty'];
    		$so_item->balance_qty 		-= $fulfilment_item['new']['fulfilment_qty'];

    		$stock 						= $so_item->Item->getQuantityOnHand();

    		if(!empty($fulfilment_item['old']))
    			$stock 					+= $fulfilment_item['old']['fulfilment_qty'];
    		$stock 						-= $fulfilment_item['new']['fulfilment_qty'];
    		$fulfilment_item['new']->setRemainingStock($stock)->save();
    		$so_item->Item->setQuantityOnHand($stock)->save();
    		$so_item->save();
    	}
    }
}
