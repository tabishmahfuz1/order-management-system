<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //

	public function getQuantityOnHand() {
    	return $this->qty_on_hand;
    }

    public function setQuantityOnHand(int $qty) {
    	$this->qty_on_hand = $qty;
    	return $this;
    }

    public function decreaseQuantityOnHand(int $qty) {
    	$this->qty_on_hand -= $qty;
    	return $this;
    }

    public function increaseQuantityOnHand(int $qty) {
    	$this->qty_on_hand += $qty;
    	return $this;
    }


    /**
    *
    * Update Item Stock by $item_id
    * @param int $item_id
    * @param int $qty
    *
    */
    public static function updateItemStockById(int $item_id, int $qty) {
    	self::where('id', $item_id)->update(['qty_on_hand' => $qty]);
    }

}
