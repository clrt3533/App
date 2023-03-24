<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_clone')->default(0);
            $table->foreignId('client_id')->constrained('users');
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->string('estimate_number')->unique();
            $table->date('date');
            $table->foreignId('status_id')->constrained();
            $table->double('sub_total', 16, 2);
            $table->enum('discount_type', ['none', 'fixed', 'percentage'])->default('none');
            $table->double('discount', 16, 2)->default(0);
            $table->double('discount_amount', 16, 2)->default(0);
            $table->double('total', 16, 2);
            $table->longText('notes')->nullable();
            $table->longText('terms')->nullable();
            $table->foreignId('created_by')->constrained('users');
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
        Schema::dropIfExists('estimates');
    }
}
