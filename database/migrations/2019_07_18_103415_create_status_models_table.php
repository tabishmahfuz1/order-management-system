<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('module');
            $table->string('code');
            $table->string('name');
            $table->string('description');
            // $table->timestamps();
        });

        DB::table('status_models')->insert(
            [
                [
                    'module'    => 'SO',
                    'code'      => 'NEW_ORDER',
                    'name'      => 'New Order',
                    'description'=>'When a New order is inserted'
                ],
                [
                    'module'    => 'SO',
                    'code'      => 'REJECTED',
                    'name'      => 'Rejected',
                    'description'=>'When an order is rejected'
                ],
                [
                    'module'    => 'SO',
                    'code'      => 'CANCELLED',
                    'name'      => 'Order Cancelled',
                    'description'=>'When an Order is Cancelled'
                ],
                [
                    'module'    => 'SO',
                    'code'      => 'IN_PROCESS',
                    'name'      => 'In Process',
                    'description'=>'When an Order is accepted'
                ],
                [
                    'module'    => 'SO',
                    'code'      => 'PARTIALLY_FULFILLED',
                    'name'      => 'Partially Fulfilled',
                    'description'=>'When an Order is Partially Fulfilled'
                ],
                [
                    'module'    => 'SO',
                    'code'      => 'FULFILLED',
                    'name'      => 'Fulfilled',
                    'description'=>'When the Order is Comletely fulfilled'
                ],
                [
                    'module'    => 'SO',
                    'code'      => 'DELIVERED',
                    'name'      => 'Delivered',
                    'description'=>'When the Order is Delivered'
                ],
                [
                    'module'    => 'SO',
                    'code'      => 'PAID',
                    'name'      => 'Paid and Closed',
                    'description'=>'When the Order paid and Closed'
                ]
            ]
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_models');
    }
}
