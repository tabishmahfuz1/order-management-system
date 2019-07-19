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
            $table->string('fulfilment_status')->after('memo')->nullable();
            $table->string('is_cancelled')->after('memo')->nullable();
            $table->string('is_invoiced')->after('memo')->nullable();
            $table->string('is_paid')->after('memo')->nullable();
        });
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
