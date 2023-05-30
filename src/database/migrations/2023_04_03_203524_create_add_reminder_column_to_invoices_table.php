<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddReminderColumnToInvoicesTable extends Migration
{

    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('reminder')->default(false);
        });
    }


    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['reminder']);
        });
    }
}
