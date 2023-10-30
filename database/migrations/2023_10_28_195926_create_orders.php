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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('amazonOrderId', 25)->primary();
            $table->timestamps();
            $table->string('accountName', 5)->nullable();
            $table->timestamp('cancellationDate')->nullable();
            $table->timestamp('earliestDeliveryDate')->nullable();
            $table->timestamp('earliestShipDate')->nullable();
            $table->timestamp('latestDeliveryDate')->nullable();
            $table->timestamp('latestShipDate')->nullable();
            $table->timestamp('purchaseDate', 3)->nullable();
            $table->enum('fulfillmentChannel', ['Seller', 'Amazon'])->default('Seller');
            $table->string('iossNumber', 50)->nullable();
            $table->boolean('isBuybackOrder')->default(false);
            $table->boolean('isIBAOrder')->default(false);
            $table->string('marketplaceTaxingSeller', 30)->default('Amazon Services, Inc.');
            $table->string('orderChannel', 30)->nullable();
            $table->string('orderMarketplaceId', 25)->nullable();
            $table->enum('OrderStatus', ['Pending', 'Confirmed', 'Shipped', 'Delivered'])->default('Delivered');
            $table->boolean('RefundApplied')->default(false);
            $table->boolean('RefundPending')->default(false);
            $table->boolean('AtRiskOfCancellation')->default(false);
            $table->boolean('isLate')->default(false);;
            $table->string('orderType', 30)->nullable();
            $table->json('paymentMethodDetails')->default('["Standard"]');
            $table->string('possibleCancelReasons', 50)->nullable();
            $table->string('replacedOrderId', 30)->nullable();
            $table->string('rootMarketplaceId', 25)->nullable();
            $table->string('salesChannel', 25)->default('Amazon.com');
            $table->string('salesChannelFlagUrl', 150)->default('https://m.media-amazon.com/images/G/01/rainier/nav/USAmazon._CB485934361_.png');
            $table->string('sellerNotes', 100)->nullable();
            $table->string('sellerOrderId', 25)->nullable();
            $table->enum('shippingService', ['Standard', 'Expedite', 'Economy'])->default('Standard');
            $table->string('taxCollectionModel', 50)->default('MarketplaceFacilitator');
            $table->string('taxResponsiblePartyName', 30)->default('Amazon Services LLC');

            $table->string('customerId', 50);
            $table->foreign('customerId')->references('customerId')->on('customer');

            $table->string('OrderItemId', 50);
            $table->foreign('OrderItemId')->references('OrderItemId')->on('order_items');

            $table->string('addressId', 50);
            $table->foreign('addressId')->references('addressId')->on('addresses');

            $table->string('PackageId', 50);
            $table->foreign('PackageId')->references('PackageId')->on('packages');

            $table->string('returnRequestId', 50);
            $table->foreign('returnRequestId')->references('returnRequestId')->on('returns');

            $table->foreign('amazonOrderId')->references('amazonOrderId')->on('order_cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
