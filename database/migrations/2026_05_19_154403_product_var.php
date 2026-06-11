<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductVar extends Migration
{
    public function up()
    {
        Schema::create('ProVariant', function (Blueprint $table) {
            $table->increments('proVarID');
            $table->integer('proID');
            $table->integer('colorID');
            $table->integer('sizeID');
            $table->integer('mateID');
            $table->string('codeSKU');
            $table->integer('price');
            $table->integer('stock');
            $table->integer('minQuantity');
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('ProVariant');
    }
}
