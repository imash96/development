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
        Schema::create('item_cost', function (Blueprint $table) {
            $table->string('OrderItemId', 50)->primary();
            $table->timestamps();
            $table->decimal('NetSalesProceeds_Amount')->default(0.00);
            $table->string('NetSalesProceeds_CurrencyCode')->nullable();
            $table->decimal('Promotion_Amount')->default(0.00);
            $table->string('Promotion_CurrencyCode')->nullable();
            $table->decimal('PromotionTax_Amount')->default(0.00);
            $table->string('PromotionTax_CurrencyCode')->nullable();
            $table->decimal('Refund_Amount')->default(0.00);
            $table->string('Refund_CurrencyCode')->nullable();
            $table->decimal('Shipping_Amount')->default(0.00);
            $table->string('Shipping_CurrencyCode')->nullable();
            $table->decimal('ShippingTax_Amount')->default(0.00);
            $table->string('ShippingTax_CurrencyCode')->nullable();
            $table->decimal('Subtotal_Amount')->default(0.00);
            $table->string('Subtotal_CurrencyCode')->nullable();
            $table->decimal('SubtotalTax_Amount')->default(0.00);
            $table->string('SubtotalTax_CurrencyCode')->nullable();
            $table->decimal('Tax_Amount')->nullable();
            $table->string('Tax_CurrencyCode')->nullable();
            $table->decimal('Total_Amount')->default(0.00);
            $table->string('Total_CurrencyCode')->nullable();
            $table->decimal('UnitPrice_Amount')->default(0.00);
            $table->string('UnitPrice_CurrencyCode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_cost');
    }
};
