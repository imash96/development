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
        Schema::create('product', function (Blueprint $table) {
            $table->string('productId', 50)->primary();
            $table->timestamps();
            $table->string('ASIN', 15)->nullable();
            $table->string('itemId', 15)->nullable();
            $table->string('size', 15)->default('Unknow');
            $table->string('color', 20)->default('Black');
            $table->string('gender', 10)->default('Unknow');
            $table->string('Condition', 25)->default('New');
            $table->string('ImageUrl', 150)->nullable();
            $table->string('HarmonizedCode', 25)->nullable();
            $table->string('LegacyListingId', 25)->nullable();
            $table->string('ProductLink', 65)->nullable();
            $table->string('SellerSKU', 25)->nullable();
            $table->string('Title')->nullable();
            $table->boolean('transparencyItem')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
};
