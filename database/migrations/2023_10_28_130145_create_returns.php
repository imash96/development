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
        Schema::create('returns', function (Blueprint $table) {
            $table->string('returnRequestId', 50)->primary();
            $table->timestamps();
            $table->string('orderItemId', 50)->nullable();
            $table->string('orderId', 25)->nullable();
            $table->string('customerId', 50)->nullable();
            $table->string("returnRequestState", 50)->default("PendingRefund");
            $table->timestamp('approveDate');
            $table->timestamp('returnRequestDate');
            $table->timestamp('closeDate');
            $table->timestamp('orderDate');
            $table->string('rmaId', 25)->nullable();
            $table->string('labelType', 30)->nullable();
            $table->string('carrierName', 30)->nullable();
            $table->string('carrierTrackingId', 30)->nullable();
            $table->decimal('labelPrice')->default(0);
            $table->string('currencyCode', 5)->nullable();
            $table->string('asin', 10)->nullable();
            $table->string('merchantSKU', 50)->nullable();
            $table->string('returnReasonStringId', 50)->nullable();
            $table->string('productImageLink', 150)->nullable();
            $table->string('productTitle', 150)->nullable();
            $table->string('returnReasonCode', 50)->nullable();
            $table->string('customerComments', 200)->nullable();
            $table->string('resolution', 50)->nullable();
            $table->string('Desc', 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('returns');
    }
};
