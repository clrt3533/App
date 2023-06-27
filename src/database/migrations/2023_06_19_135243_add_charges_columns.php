<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChargesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('packing');
            $table->dropColumn('unloading');
            $table->dropColumn('local');
            $table->dropColumn('gst');
            $table->dropColumn('transport');
            $table->dropColumn('unpacking');
            $table->dropColumn('car_transport');
            $table->dropColumn('loading');
            $table->dropColumn('ac');
            $table->dropColumn('insuarance');
        });
    }
}
