<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class ItemStockDetail extends Model
{
    //
    public const RECEIVING = 'RECEIVING';
    public const OPENING_STOCK = 'OPENING_STOCK';
    public const ADJUSTMENT = 'ADJUSTMENT';

    public function Item() {
    	return $this->belongsTo(Item::class, 'item_id');
    }

    public static function saveItemStock($item_stock_detail, $itemStockDetail = null) {
        DB::transaction(function() use(&$itemStockDetail, $item_stock_detail){
            if($itemStockDetail instanceof ItemStockDetail){
                // Should already be implicitly bounded by Laravel in Controller
            }
            if(!empty($item_stock_detail['item_stock_detail_id'])) {
                $itemStockDetail = self::find($item_stock_detail['item_stock_detail_id']);
                $itemStockDetail->Item->decreaseQuantityOnHand($itemStockDetail->quantity);
            } else {
                $itemStockDetail = new self();
            }
            $itemStockDetail->type = $item_stock_detail['type'];
            $itemStockDetail->date = $item_stock_detail['date'];
            $itemStockDetail->quantity = $item_stock_detail['quantity'];
            $itemStockDetail->remarks = $item_stock_detail['remarks'];
            $itemStockDetail->item_id = $item_stock_detail['item_id'];
            $itemStockDetail->save();
            $itemStockDetail->Item->increaseQuantityOnHand($itemStockDetail->quantity)
                                    ->save();
        });
    	
    	return $itemStockDetail;
    }
}
