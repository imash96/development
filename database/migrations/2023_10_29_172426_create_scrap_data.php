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
        Schema::create('scrap_data', function (Blueprint $table) {
            $table->string('scrap_id', 50)->primary();
            $table->timestamps();
            $table->string('account_name', 6)->unique();
            $table->string('account_email', 50)->unique();
            $table->string('seller_name', 50);
            $table->string('store_name', 30);
            $table->string('contact_number', 15);
            $table->enum('status', ['Active', 'Pending', 'Suspended']);
            $table->string('lwaClientId', 70)->nullable();
            $table->string('lwaClientSecret', 90)->nullable();
            $table->string('awsAccessKeyId', 30)->nullable();
            $table->string('awsSecretAccessKey', 50)->nullable();
            $table->string('roleArn', 50)->nullable();
            $table->string('lwaRefreshToken', 400)->nullable();
            $table->boolean('isFirstScrap')->default(true);
            $table->timestamp('last_scrap')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scrap_data');
    }
};
