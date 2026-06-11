<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('admin')->insert([
            'adminName' => 'Administrator',
            'adminPhone' => '',
            'adminAddress' => '',
            'adminEmail' => 'admin@gmail.com',
            'adminPass' => '$2y$10$pmNHwQhyhP.dmPUxVMXzQOtB9IUo3q5NYqJSpaAvGEMI8aK5eyVx6',
            'secret_key' => 3745821,
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('categories')->insert([
            ['cateName' => 'Áo thun', 'slug' => 'ao-thun', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['cateName' => 'Áo sơ mi', 'slug' => 'ao-so-mi', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['cateName' => 'Quần jean', 'slug' => 'quan-jean', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['cateName' => 'Quần short', 'slug' => 'quan-short', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['cateName' => 'Váy đầm', 'slug' => 'vay-dam', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['cateName' => 'Áo khoác', 'slug' => 'ao-khoac', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['cateName' => 'Phụ kiện', 'slug' => 'phu-kien', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('color')->insert([
            ['colorValue' => 'Đen', 'hex' => '#000000', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['colorValue' => 'Trắng', 'hex' => '#FFFFFF', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['colorValue' => 'Đỏ', 'hex' => '#E53935', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['colorValue' => 'Xanh navy', 'hex' => '#1A237E', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['colorValue' => 'Xanh lá', 'hex' => '#43A047', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['colorValue' => 'Be', 'hex' => '#D7CCC8', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['colorValue' => 'Xám', 'hex' => '#9E9E9E', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['colorValue' => 'Hồng', 'hex' => '#F48FB1', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('size')->insert([
            ['sizeValue' => 'XS', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['sizeValue' => 'S', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['sizeValue' => 'M', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['sizeValue' => 'L', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['sizeValue' => 'XL', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['sizeValue' => 'XXL', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['sizeValue' => '28', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['sizeValue' => '30', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['sizeValue' => '32', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['sizeValue' => '34', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('brand')->insert([
            ['brandName' => 'Ekka', 'brandDesc' => 'Thương hiệu thời trang nội địa', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['brandName' => 'Routine', 'brandDesc' => 'Thời trang trẻ trung, năng động', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['brandName' => 'IVY moda', 'brandDesc' => 'Thời trang công sở và dạo phố', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['brandName' => 'Canifa', 'brandDesc' => 'Thương hiệu thời trang gia đình', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['brandName' => 'Yody', 'brandDesc' => 'Thời trang nam nữ hiện đại', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('material')->insert([
            ['mateName' => 'Cotton 100%', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['mateName' => 'Polyester', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['mateName' => 'Cotton pha', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['mateName' => 'Denim', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['mateName' => 'Linen', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['mateName' => 'Kaki', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['mateName' => 'Nỉ', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['mateName' => 'Da', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->call(BooProductSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
