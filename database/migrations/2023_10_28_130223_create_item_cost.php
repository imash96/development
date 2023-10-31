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
            $table->string('CurrencyCode')->nullable();
            $table->decimal('Promotion_Amount')->default(0.00);
            $table->decimal('PromotionTax_Amount')->default(0.00);
            $table->decimal('Refund_Amount')->default(0.00);
            $table->decimal('Shipping_Amount')->default(0.00);
            $table->decimal('ShippingTax_Amount')->default(0.00);
            $table->decimal('Subtotal_Amount')->default(0.00);
            $table->decimal('SubtotalTax_Amount')->default(0.00);
            $table->decimal('Tax_Amount')->default(0.00);
            $table->decimal('Total_Amount')->default(0.00);
            $table->decimal('UnitPrice_Amount')->default(0.00);
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
