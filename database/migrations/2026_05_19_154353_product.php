<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Product extends Migration
{
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('proID');
            $table->integer('cateID');
            $table->integer('brandID');
            $table->string('proName');
            $table->longText('proDesc')->nullable();
            $table->longText('IMG')->nullable();
            $table->string('slug')->default('');
            $table->longText('banner')->nullable();
            $table->longText('detail')->nullable();
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
}
