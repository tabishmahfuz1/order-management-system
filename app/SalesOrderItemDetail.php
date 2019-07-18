<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItemDetail extends Model
{
    public function Item() {
        return $this->belongsTo(Item::class);
    }

    public function getOrderedQty() {
        return $this->item_qty;
    }

    public function getBalanceQty() {
        return $this->balance_qty;
    }

    public function isCompletelyFulfilled() {
        return ($this->balance_qty == 0);
    }

    public function getFulfilledQty() {
        return ($this->item_qty - ($this->balance_qty ?? 0));
    }


    //
    /**
	 * Returns Items with Item Name for a particular Order identified by the parameter order_id
	 * 
	 * @param  int  $order_id
     * @return array
     *
     */
    public static function getOrderItems($order_id) {
    	return self::where('sales_order_id', $order_id)->join('items', 'items.id', '=', 'sales_order_item_details.item_id')->select('sales_order_item_details.*', 'items.item_name')->get();
    }
}
