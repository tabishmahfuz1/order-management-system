<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->integer('fulfilment_status')->after('memo')->default(0);
            $table->integer('is_cancelled')->after('memo')->default(0);
            $table->integer('is_invoiced')->after('memo')->default(0);
            $table->integer('is_paid')->after('memo')->default(0);
        });

        /*
            ALTER TABLE `sales_orders` CHANGE `is_paid` `is_paid` TINYINT NOT NULL DEFAULT '0', CHANGE `is_invoiced` `is_invoiced` TINYINT NOT NULL DEFAULT '0', CHANGE `is_cancelled` `is_cancelled` BOOLEAN NOT NULL DEFAULT FALSE, CHANGE `fulfilment_status` `fulfilment_status` TINYINT NOT NULL DEFAULT '0';
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('fulfilment_status');
            $table->dropColumn('is_cancelled');
            $table->dropColumn('is_invoiced');
            $table->dropColumn('is_paid');
        });
    }
}
