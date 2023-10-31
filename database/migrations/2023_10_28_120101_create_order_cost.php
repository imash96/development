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
        Schema::create('order_cost', function (Blueprint $table) {
            $table->string('amazonOrderId', 50)->primary();
            $table->timestamps();
            $table->decimal('GrandTotal_Amount')->default(0.00);
            $table->string('CurrencyCode')->default('USD');
            $table->decimal('NetSalesProceeds_Amount')->default(0.00);
            $table->decimal('PromotionTotal_Amount')->default(0.00);
            $table->decimal('PromotionTotalTax_Amount')->default(0.00);
            $table->decimal('RefundTotal_Amount')->default(0.00);
            $table->decimal('ShippingTotal_Amount')->default(0.00);
            $table->decimal('ShippingTotalTax_Amount')->default(0.00);
            $table->decimal('TaxTotal_Amount')->default(0.00);
            $table->decimal('Total_Amount')->default(0.00);
            $table->decimal('Commission_Amount')->default(0.00);
            $table->decimal('INR_Amount')->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_cost');
    }
};
