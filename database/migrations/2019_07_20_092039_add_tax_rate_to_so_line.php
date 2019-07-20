<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxRateToSoLine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_order_item_details', function (Blueprint $table) {
            //
            $table->decimal('tax_rate', 5, 2)->default(0)->after('item_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_order_item_details', function (Blueprint $table) {
            //
            $table->dropColumn('tax_rate');
        });
    }
}
