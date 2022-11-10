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
        Schema::create('warehouse_manages', function (Blueprint $table) {
            $table->id();
            $table->string('no')->nullable()->comment('編號');
            $table->integer('store_id')->comment('店家id');
            $table->integer('company_id')->nullable()->comment('廠商ID');
            $table->string('name')->comment('變更名稱');
            $table->tinyinteger('type')->comment('1:一般、2:進貨、3:退貨、4:退料、5:報廢');
            $table->integer('price')->comment('金額');
            $table->boolean('is_account')->default(true)->comment('是否連動帳務紀錄');
            $table->date('date')->comment('日期');
            $table->text('remark')->nullable()->comment('備註');
            $table->text('change_info')->nullable()->comment('更改資訊,Json格式');
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
        Schema::dropIfExists('warehouse_manages');
    }
};
