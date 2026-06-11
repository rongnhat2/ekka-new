<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WarehouseImport extends Migration
{
    public function up()
    {
        Schema::create('stock_import', function (Blueprint $table) {
            $table->increments('importID');
            $table->integer('adminID');
            $table->timestamp('improtDate')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('totalQuantity')->default(0);
            $table->text('note')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('stock_import_detail', function (Blueprint $table) {
            $table->increments('importDetailID');
            $table->integer('importID');
            $table->integer('proVarID');
            $table->integer('Quantity');
            $table->integer('unitPrice')->default(0);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_import_detail');
        Schema::dropIfExists('stock_import');
    }
}
