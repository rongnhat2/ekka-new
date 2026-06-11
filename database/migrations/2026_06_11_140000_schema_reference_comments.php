<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchemaReferenceComments extends Migration
{
    private $tableComments = [
        'categories' => 'Danh mục sản phẩm',
        'brand' => 'Thương hiệu',
        'product' => 'Sản phẩm cha (SPU)',
        'ProVariant' => 'Biến thể SKU',
        'color' => 'Màu sắc',
        'size' => 'Kích cỡ',
        'material' => 'Chất liệu',
        'stock_import' => 'Phiếu nhập kho',
        'stock_import_detail' => 'Chi tiết nhập kho',
        'user' => 'Khách hàng',
        'order' => 'Đơn hàng',
        'orderDetail' => 'Chi tiết đơn hàng',
        'payment' => 'Thanh toán',
        'admin' => 'Tài khoản quản trị',
        'media' => 'Thư viện file/media',
    ];

    public function up()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->tableComments as $table => $comment) {
            if (Schema::hasTable($table)) {
                DB::statement("ALTER TABLE `{$table}` COMMENT = " . DB::getPdo()->quote($comment));
            }
        }
    }

    public function down()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach (array_keys($this->tableComments) as $table) {
            if (Schema::hasTable($table)) {
                DB::statement("ALTER TABLE `{$table}` COMMENT = ''");
            }
        }
    }
}
