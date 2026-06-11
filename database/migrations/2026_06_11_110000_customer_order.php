<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerOrder extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('userID');
            $table->string('userName');
            $table->string('userPhone');
            $table->string('userEmail');
            $table->string('userAddress')->default('');
            $table->string('userPass')->nullable();
            $table->integer('secret_key')->nullable();
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('order', function (Blueprint $table) {
            $table->increments('ordID');
            $table->integer('userID');
            $table->timestamp('ordDate')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->string('ordPhone');
            $table->string('ordReceiver');
            $table->string('ordAddress');
            $table->integer('totalPrice');
            $table->integer('staValue')->default(0);
            $table->integer('subtotal')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('order_type')->default(0);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('orderDetail', function (Blueprint $table) {
            $table->increments('ordDetailID');
            $table->integer('ordID');
            $table->integer('proID');
            $table->integer('proVarID');
            $table->string('proName');
            $table->integer('quantity');
            $table->integer('basePrice');
            $table->integer('salePrice');
            $table->integer('discount')->default(0);
            $table->integer('suborder_status')->default(0);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('payment', function (Blueprint $table) {
            $table->increments('payID');
            $table->integer('ordID');
            $table->timestamp('payDate')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('payStatus')->default(1);
            $table->integer('amount');
            $table->string('payMethod')->default('COD');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment');
        Schema::dropIfExists('orderDetail');
        Schema::dropIfExists('order');
        Schema::dropIfExists('user');
    }
}
