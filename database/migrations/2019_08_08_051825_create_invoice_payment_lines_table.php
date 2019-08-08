<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicePaymentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payment_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('invoice_id')->index();
            $table->integer('line_num');
            $table->decimal('received_amt', 10, 2);
            $table->date('date_received');
            $table->decimal('balance_amt');
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
        Schema::dropIfExists('invoice_payment_lines');
    }
}
