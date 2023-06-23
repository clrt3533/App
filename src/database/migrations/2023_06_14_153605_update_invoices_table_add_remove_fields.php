<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInvoicesTableAddRemoveFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Remove the foreign key constraint
            $table->dropForeign(['client_id']);
            $table->dropForeign(['status_id']);

            // Remove the column
            $table->dropColumn('client_id');
            $table->dropColumn('status_id');

            $table->string('due_date')->nullable()->change();

            // Add new columns
            $table->string('client_name')->nullable()->after('invoice_number');
            $table->string('client_email')->nullable()->after('client_name');
            $table->string('client_number')->nullable()->after('client_email');
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
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }
}
