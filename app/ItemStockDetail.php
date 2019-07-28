<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemStockDetail extends Model
{
    //
    public const RECEIVING = 'RECEIVING';
    public const OPENING_BALANCE = 'OPENING_BALANCE';
    public const ADJUSTMENT = 'ADJUSTMENT';
}
