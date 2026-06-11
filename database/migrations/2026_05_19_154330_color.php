<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Color extends Migration
{
    public function up()
    {
        Schema::create('color', function (Blueprint $table) {
            $table->increments('colorID');
            $table->string('colorValue');
            $table->string('hex')->default('#000000');
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('color');
    }
}
