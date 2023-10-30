<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->string('customerId', 50)->primary();
            $table->timestamps();
            $table->string('buyerName', 50)->nullable();
            $table->string('buyerPONumber', 50)->nullable();
            $table->string('buyerVatNumber', 50)->nullable();
            $table->string('buyerLegalName', 50)->nullable();
            $table->string('buyerCompanyName', 50)->nullable();
            $table->json('badges')->nullable();
            $table->boolean('verifiedBusinessBuyer')->default(false);
            $table->boolean('isRepeatCustomer')->default(false);
            $table->string('buyerProxyEmail', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
};
