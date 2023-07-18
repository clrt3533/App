<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payment_histories')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->date('date');

            $table->string('client_name');
            $table->string('client_phone');

            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->double('amount', 16, 2);
            $table->longText('amount_words');

            $table->string('from');
            $table->string('to');

            $table->foreignId('created_by')->constrained('users');
            $table->softDeletes();
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
        Schema::dropIfExists('receipt_histories');
    }
}
