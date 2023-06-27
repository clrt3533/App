<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePriceTaxIdPackagesFromInvoiceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            // Cannot use normal remove foreign key due to contraint error, hence the below option
            Schema::dropIfExists('tax_id');
            // $table->dropForeign(['tax_id']);

            $table->dropColumn('price');
            // $table->dropColumn('tax_id');
            $table->dropColumn('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->double('price', 16, 2);
            $table->foreignId('tax_id')->nullable()->constrained();
            $table->tinyInteger('packages')->nullable();
        });
    }
}
