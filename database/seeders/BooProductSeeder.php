<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BooProductSeeder extends Seeder
{
    private $dataDir;

    public function run()
    {
        $this->dataDir = database_path('seeders/data/boo-crawl');
        $jsonPath = $this->dataDir . '/products.json';

        if (!File::exists($jsonPath)) {
            if ($this->command) {
                $this->command->warn('Không tìm thấy products.json tại ' . $jsonPath);
            }
            return;
        }

        $payload = json_decode(File::get($jsonPath), true);
        $products = $payload['products'] ?? [];

        if (!$products) {
            return;
        }

        $now = now();
        $brandId = $this->ensureBooBrand($now);
        $categoryId = DB::table('category')->where('slug', 'ao-thun')->value('id') ?: 1;
        $colorId = DB::table('color')->where('name', 'Trắng')->value('id') ?: 1;
        $sizeId = DB::table('size')->where('name', 'M')->value('id') ?: 1;
        $materialId = DB::table('material')->where('name', 'Cotton 100%')->value('id') ?: 1;

        $uploadDir = public_path('uploads/media');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $usedSlugs = [];

        foreach ($products as $item) {
            $sku = $item['sku'] ?? null;
            if ($sku && DB::table('product_var')->where('codeSKU', $sku)->exists()) {
                continue;
            }

            $name = $item['name'];
            $price = (int) ($item['price']['value'] ?? 0);
            $slug = $this->uniqueSlug($this->toSlug($name), $usedSlugs);
            $usedSlugs[] = $slug;

            $imagePaths = $this->importImages($item['images'] ?? [], $uploadDir, $now);
            $imagesValue = $imagePaths ? implode(',', $imagePaths) : '[]';
            $banner = $imagePaths[0] ?? null;

            $description = sprintf(
                'Sản phẩm thời trang BOO — %s. Giá tham khảo: %s đ.',
                $name,
                number_format($price)
            );

            $detail = '<p>' . e($description) . '</p>';
            if (!empty($item['url'])) {
                $detail .= '<p>Nguồn: <a href="' . e($item['url']) . '" target="_blank" rel="noopener">boo.vn</a></p>';
            }

            $productId = DB::table('product')->insertGetId([
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'name' => $name,
                'slug' => $slug,
                'images' => $imagesValue,
                'banner' => $banner,
                'description' => $description,
                'detail' => $detail,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('product_var')->insert([
                'product_id' => $productId,
                'color_id' => $colorId,
                'size_id' => $sizeId,
                'material_id' => $materialId,
                'codeSKU' => $sku ?: ('BOO-' . $productId),
                'prices' => $price,
                'stock' => 0,
                'minQuantity' => 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function ensureBooBrand($now): int
    {
        $existing = DB::table('brand')->where('name', 'BOO')->value('id');
        if ($existing) {
            return (int) $existing;
        }

        return (int) DB::table('brand')->insertGetId([
            'name' => 'BOO',
            'description' => 'Thương hiệu thời trang BOO / BOOLAAB',
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function importImages(array $images, string $uploadDir, $now): array
    {
        $paths = [];

        foreach ($images as $image) {
            $filename = $image['filename'] ?? basename($image['local_path'] ?? '');
            if (!$filename) {
                continue;
            }

            $source = $this->dataDir . '/images/' . $filename;
            if (!File::exists($source)) {
                continue;
            }

            $destName = time() . '_' . uniqid() . '_' . $filename;
            $destPath = $uploadDir . '/' . $destName;
            File::copy($source, $destPath);

            $relativePath = 'uploads/media/' . $destName;
            $paths[] = $relativePath;

            DB::table('media')->insert([
                'name' => $filename,
                'path' => $relativePath,
                'mime_type' => 'image/webp',
                'size' => filesize($destPath) ?: 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $paths;
    }

    private function uniqueSlug(string $slug, array $used): string
    {
        $base = $slug ?: 'san-pham';
        $candidate = $base;
        $i = 1;

        while (
            in_array($candidate, $used, true)
            || DB::table('product')->where('slug', $candidate)->exists()
        ) {
            $candidate = $base . '-' . $i;
            $i++;
        }

        return $candidate;
    }

    private function toSlug(string $str): string
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/(\[|\])/', '', $str);
        $str = preg_replace('/([^a-z0-9\-]+)/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);

        return trim($str, '-');
    }
}
