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
            $table->id();
            $table->string('no')->comment('訂單編號');
            $table->integer('store_id')->comment('店家id');
            $table->integer('total_price')->nullable()->comment('總價格');
            $table->integer('floating_price')->nullable()->comment('浮動價格');
            $table->integer('final_price')->nullable()->comment('最後價格');

            $table->text('order_info')->nullable()->comment('訂單資訊 : Json格式');

            $table->text('remark')->nullable()->comment('訂單備註');
            $table->tinyinteger('status')->comment('0:取消、1:完成、2:下訂');
            $table->tinyinteger('is_checkout')->comment('0:未結帳、1:已結帳');
            
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
        Schema::dropIfExists('orders');
    }
};
