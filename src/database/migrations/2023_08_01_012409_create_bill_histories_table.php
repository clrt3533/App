<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            
            $table->double('packing', 16, 2)->default(0);
            $table->double('unloading', 16, 2)->default(0);
            $table->double('local', 16, 2)->default(0);
            $table->double('gst', 16, 2)->default(0);
            $table->double('transport', 16, 2)->default(0);
            $table->double('unpacking', 16, 2)->default(0);
            $table->double('car_transport', 16, 2)->default(0);
            $table->double('loading', 16, 2)->default(0);
            $table->double('ac', 16, 2)->default(0);
            $table->double('insuarance', 16, 2)->default(0);

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
        Schema::dropIfExists('bill_histories');
    }
}
