<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Admin extends Migration
{
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('adminID');
            $table->string('adminName')->default('');
            $table->string('adminPhone')->default('');
            $table->string('adminAddress')->default('');
            $table->string('adminEmail');
            $table->string('adminPass');
            $table->integer('secret_key')->nullable();
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
