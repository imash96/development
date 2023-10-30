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
        Schema::create('addresses', function (Blueprint $table) {
            $table->string('addressId', 50)->primary();
            $table->timestamps();
            $table->string('messageStr', 100)->nullable();
            $table->string('addressType', 25)->default('Residential')->nullable();
            $table->string('name', 50)->default('Amazon CUST');
            $table->string('companyName', 50)->nullable();
            $table->string('line1', 60)->nullable();
            $table->string('line2', 50)->nullable();
            $table->string('line3', 50)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('county', 30)->nullable();
            $table->string('municipality', 30)->nullable();
            $table->string('postalCode', 10)->nullable();
            $table->string('countryCode', 5)->nullable();
            $table->string('countryLine', 30)->nullable();
            $table->string('phoneNumber', 25)->nullable();
            $table->string('label', 100)->nullable();
            $table->boolean('confidentialVisibleAddress')->default(false);

            $table->string('customerId', 50);
            $table->foreign('customerId')->references('customerId')->on('customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
