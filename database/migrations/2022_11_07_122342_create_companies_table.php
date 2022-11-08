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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->comment('店家id');
            $table->string('name')->comment('名稱');
            $table->string('tax_number')->nullable()->comment('統一編號');
            $table->string('address')->nullable()->comment('地址');
            $table->string('contact_person')->nullable()->comment('聯絡人');
            $table->string('contact_phone')->nullable()->comment('聯絡電話');
            $table->string('contact_url')->nullable()->comment('聯絡網址');
            $table->text('remark')->nullable()->comment('備註');
            $table->integer('sort')->default(1)->comment('排序');
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
        Schema::dropIfExists('companies');
    }
};
