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
            $table->string('GrandTotal_CurrencyCode')->nullable();
            $table->decimal('NetSalesProceeds_Amount')->default(0.00);
            $table->string('NetSalesProceeds_CurrencyCode')->nullable();
            $table->decimal('PromotionTotal_Amount')->default(0.00);
            $table->string('PromotionTotal_CurrencyCode')->nullable();
            $table->decimal('PromotionTotalTax_Amount')->default(0.00);
            $table->string('PromotionTotalTax_CurrencyCode')->nullable();
            $table->decimal('RefundTotal_Amount')->default(0.00);
            $table->string('RefundTotal_CurrencyCode')->nullable();
            $table->decimal('ShippingTotal_Amount')->default(0.00);
            $table->string('ShippingTotal_CurrencyCode')->nullable();
            $table->decimal('ShippingTotalTax_Amount')->default(0.00);
            $table->string('ShippingTotalTax_CurrencyCode')->nullable();
            $table->decimal('TaxTotal_Amount')->nullable();
            $table->string('TaxTotal_CurrencyCode')->nullable();
            $table->decimal('Total_Amount')->default(0.00);
            $table->string('Total_CurrencyCode')->nullable();
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
