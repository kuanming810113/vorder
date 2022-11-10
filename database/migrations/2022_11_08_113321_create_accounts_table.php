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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->comment('店家id');
            $table->integer('warehouse_manage_id')->nullable()->comment('倉庫管理ID');
            $table->integer('company_id')->nullable()->comment('供應商ID');
            $table->string('name')->comment('成本名稱');
            $table->integer('price')->nullable()->comment('成本金額');
            $table->date('date')->nullable()->comment('日期');
            $table->text('remark')->nullable()->comment('備註');
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
        Schema::dropIfExists('accounts');
    }
};
