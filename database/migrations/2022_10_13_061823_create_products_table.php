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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->comment('店家id');
            $table->string('name')->comment('商品名稱');
            $table->text('intro')->nullable()->comment('商品介紹');
            $table->integer('weight')->nullable()->comment('重量');
            $table->string('volume')->nullable()->comment('體積');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
