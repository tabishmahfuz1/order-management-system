<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    //
	public function Items() {
		return $this->hasMany('App\SalesOrderItemDetail', 'sales_order_id');
	}

    public function addItems(array $items) {
    	foreach ($items as $r_item) {
    		if(isset($r_item['so_item_id']))
    			$item = SalesOrderItemDetail::find($r_item['so_item_id']);
    		else
    			$item = new SalesOrderItemDetail();

    		$item->item_id        = $r_item['item_id'];
    		$item->item_cost      = $r_item['item_cost'];
            $item->item_price     = $r_item['item_price'];
            $item->item_disc_per    = $r_item['item_disc_per'];
            $item->item_disc_amt = $r_item['item_disc_amt'];
            $item->item_rate = $r_item['item_rate'];
    		$item->item_qty_on_hand = $r_item['qty_on_hand'] ?? 0;
    		$item->item_qty = $r_item['item_qty'];
    		$item->item_total = $r_item['item_total'];
    		$item->sales_order_id = $this->id;
    		$item->save();
    	}
    	return $item;
    }
}
