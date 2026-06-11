<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WarehouseImport extends Migration
{
    public function up()
    {
        Schema::create('warehouse_import', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('warehouse_import_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('import_id');
            $table->integer('product_var_id');
            $table->integer('quantity');
            $table->integer('price');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_import_detail');
        Schema::dropIfExists('warehouse_import');
    }
}
