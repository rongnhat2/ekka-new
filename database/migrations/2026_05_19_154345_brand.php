<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Brand extends Migration
{
    public function up()
    {
        Schema::create('brand', function (Blueprint $table) {
            $table->increments('brandID');
            $table->string('brandName');
            $table->string('brandDesc')->default('');
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('brand');
    }
}
