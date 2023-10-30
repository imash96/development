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
        Schema::create('order_items', function (Blueprint $table) {
            $table->string('OrderItemId', 50)->primary();
            $table->timestamps();
            $table->boolean('isBuyerRequestedCancel')->default(false);
            $table->string('cancelReason', 20)->nullable();
            $table->string('CustomerOrderItemCode', 100)->nullable();
            $table->boolean('Gift')->default(false);
            $table->string('GiftMessage', 100)->nullable();
            $table->string('GiftWrapType', 25)->nullable();
            $table->boolean('ImageSourceSarek')->default(false);
            $table->string('ItemCustomizations', 100)->nullable();
            $table->string('CancelStatus', 15)->default('None');
            $table->string('ItemStatus', 15)->default('Shipped');
            $table->string('ProductCustomizations', 100)->nullable();
            $table->smallInteger('QuantityCanceled')->default(0);
            $table->smallInteger('QuantityOrdered')->default(1);
            $table->smallInteger('QuantityShipped')->default(1);
            $table->smallInteger('QuantityUnshipped')->default(0);
            $table->boolean('SignatureRecommended')->default(false);
            $table->string('productId', 50);
            $table->foreign('productId')->references('productId')->on('product');
            $table->foreign('OrderItemId')->references('OrderItemId')->on('item_cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
