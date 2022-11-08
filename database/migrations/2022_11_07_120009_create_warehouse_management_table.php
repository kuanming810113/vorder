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
        Schema::create('warehouse_management', function (Blueprint $table) {
            $table->id();
            $table->string('no')->comment('更動編號');
            $table->integer('store_id')->comment('店家id');
            $table->integer('cost_id')->comment('成本ID');
            $table->integer('company_id')->nullable()->comment('廠商ID');
            $table->string('name')->comment('變更名稱');
            $table->tinyinteger('type')->comment('0:一般、1:進貨、2:退貨、3:消貨');
            $table->integer('price')->comment('金額');
            $table->date('date')->comment('日期');
            $table->text('remark')->nullable()->comment('備註');
            $table->text('change_info')->comment('更改資訊,Json格式');
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
        Schema::dropIfExists('warehouse_management');
    }
};
