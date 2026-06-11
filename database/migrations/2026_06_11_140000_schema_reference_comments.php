<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Documents canonical table/column names in DB metadata.
 * See config/schema.php for the authoritative schema map used by application code.
 */
class SchemaReferenceComments extends Migration
{
    private $tableComments = [
        'category' => 'Danh mục sản phẩm (NOT categories)',
        'brand' => 'Thương hiệu',
        'product' => 'Sản phẩm cha (SPU)',
        'product_var' => 'Biến thể SKU (NOT ProVariant). prices=giá bán, stock=tồn kho',
        'color' => 'Màu sắc (name, hex)',
        'size' => 'Kích cỡ (name)',
        'material' => 'Chất liệu (name)',
        'warehouse_import' => 'Phiếu nhập kho (NOT stock_import)',
        'warehouse_import_detail' => 'Chi tiết nhập kho. price=giá nhập',
        'customer' => 'Khách hàng (NOT user)',
        'orders' => 'Đơn hàng (NOT order)',
        'order_detail' => 'Chi tiết đơn (NOT orderDetail). price=giá tại thời điểm đặt',
        'admin' => 'Tài khoản quản trị',
        'media' => 'Thư viện file/media',
    ];

    public function up()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->tableComments as $table => $comment) {
            if ($this->tableExists($table)) {
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
            if ($this->tableExists($table)) {
                DB::statement("ALTER TABLE `{$table}` COMMENT = ''");
            }
        }
    }

    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }
}
