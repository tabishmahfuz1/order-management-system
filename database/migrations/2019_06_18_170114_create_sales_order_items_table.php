<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_item_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id');
            $table->decimal('item_cost', 10, 2);
            $table->decimal('item_price', 10, 2);
            $table->decimal('item_disc_per', 5, 2)->default(0);
            $table->decimal('item_disc_amt', 5, 2)->default(0);
            $table->decimal('item_rate', 10, 2);
            $table->integer('item_qty');
            $table->bigInteger('item_qty_on_hand');
            $table->decimal('item_total', 10, 2);
            $table->integer('sales_order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_item_details');
    }
}
