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
            'secret_key' => 3745821,
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$pmNHwQhyhP.dmPUxVMXzQOtB9IUo3q5NYqJSpaAvGEMI8aK5eyVx6',
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('category')->insert([
            ['name' => 'Áo thun', 'slug' => 'ao-thun', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Áo sơ mi', 'slug' => 'ao-so-mi', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quần jean', 'slug' => 'quan-jean', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quần short', 'slug' => 'quan-short', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Váy đầm', 'slug' => 'vay-dam', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Áo khoác', 'slug' => 'ao-khoac', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('color')->insert([
            ['name' => 'Đen', 'hex' => '#000000', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Trắng', 'hex' => '#FFFFFF', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Đỏ', 'hex' => '#E53935', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Xanh navy', 'hex' => '#1A237E', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Xanh lá', 'hex' => '#43A047', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Be', 'hex' => '#D7CCC8', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Xám', 'hex' => '#9E9E9E', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hồng', 'hex' => '#F48FB1', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('size')->insert([
            ['name' => 'XS', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'S', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'M', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'L', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'XL', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'XXL', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => '28', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => '30', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => '32', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => '34', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('brand')->insert([
            ['name' => 'Ekka', 'description' => 'Thương hiệu thời trang nội địa', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Routine', 'description' => 'Thời trang trẻ trung, năng động', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'IVY moda', 'description' => 'Thời trang công sở và dạo phố', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Canifa', 'description' => 'Thương hiệu thời trang gia đình', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Yody', 'description' => 'Thời trang nam nữ hiện đại', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('material')->insert([
            ['name' => 'Cotton 100%', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Polyester', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cotton pha', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Denim', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Linen', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kaki', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nỉ', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Da', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->call(BooProductSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
