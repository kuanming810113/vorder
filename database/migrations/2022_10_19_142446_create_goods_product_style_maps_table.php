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
        Schema::create('goods_product_style_maps', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->comment('店家id');
            $table->integer('goods_id')->comment('發布商品id');
            $table->integer('product_id')->comment('商品id');
            $table->integer('product_style_id')->comment('商品樣式id');
            $table->integer('goods_combo_id')->comment('組合id');
            $table->integer('extra_price')->default(0)->comment('加價金額');
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
        Schema::dropIfExists('goods_product_style_maps');
    }
};
