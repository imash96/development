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
        Schema::create('packages', function (Blueprint $table) {
            $table->string('PackageId', 50)->primary();
            $table->timestamps();
            $table->string('Carrier')->nullable();
            $table->boolean('IsSignatureConfirmationApplied')->default(false);
            $table->string('ShipmentId')->nullable();
            $table->timestamp('ShipDate')->nullable();
            $table->string('ShippingService')->nullable();
            $table->string('TrackingId')->nullable();
            $table->string('deliveryDesc', 50)->default('Not Available');
            $table->string('deliveryStatus', 25)->default('Shipped');
            $table->string('lastLocation', 50)->default('Unknow');
            $table->dateTimeTz('delivereyDate')->nullable();
            $table->dateTimeTz('estimatedTimeOfDelivery')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
