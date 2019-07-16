<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItemDetail extends Model
{
    public function Item() {
        return $this->belongsTo(Item::class);
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
