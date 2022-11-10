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
        Schema::create('change_inventory_records', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->comment('店家id');
            $table->integer('order_id')->nullable()->comment('訂單id');
            $table->integer('warehouse_manage_id')->nullable()->comment('倉儲管理id');
            $table->integer('product_id')->comment('商品id');
            $table->integer('product_style_id')->comment('商品樣式id');
            $table->integer('change_amount')->comment('變動數量');
            $table->tinyinteger('type')->comment('1:一般、2:進貨、3:退貨、4:退料、5:報廢、6:訂單 ');
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
        Schema::dropIfExists('change_inventory_records');
    }
};
