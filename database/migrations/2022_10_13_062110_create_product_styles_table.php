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
        Schema::create('product_styles', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->comment('店家id');
            $table->integer('product_id')->comment('商品id');
            $table->string('name')->comment('樣式名稱');
            $table->integer('amount')->comment('數量');  
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
        Schema::dropIfExists('product_styles');
    }
};
