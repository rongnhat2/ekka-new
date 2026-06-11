<?php

namespace App\Http\Controllers\Customer\Concerns;

use DB;

trait LoadsProducts
{
    protected function productImage($images): string
    {
        if (!$images || $images === '[]') {
            return '';
        }
        $first = trim(explode(',', $images)[0]);
        return $first ? '/' . ltrim($first, '/') : '';
    }

    protected function attachVariantSummary($products)
    {
        foreach ($products as $product) {
            $product->image_url = $this->productImage($product->images);
            $var = DB::table('product_var')
                ->where('product_id', $product->id)
                ->where('status', 1)
                ->orderBy('prices')
                ->first();
            $product->min_price = $var ? $var->prices : 0;
        }

        return $products;
    }

    protected function getProductWithVariants($id)
    {
        $product = DB::table('product')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
            ->where('product.id', $id)
            ->select('product.*', 'category.name as category_name', 'brand.name as brand_name')
            ->first();

        if (!$product) {
            return null;
        }

        $product->image_list = array_filter(array_map('trim', explode(',', $product->images ?: '')));
        $product->variants = DB::select('
            SELECT product_var.*,
                color.name AS color_name, color.hex AS color_hex,
                size.name AS size_name,
                material.name AS material_name
            FROM product_var
            LEFT JOIN color ON color.id = product_var.color_id
            LEFT JOIN size ON size.id = product_var.size_id
            LEFT JOIN material ON material.id = product_var.material_id
            WHERE product_var.product_id = ? AND product_var.status = 1
            ORDER BY product_var.id ASC
        ', [$id]);

        return $product;
    }

    protected function getCartLines(array $cart): array
    {
        $lines = [];
        foreach ($cart as $varId => $qty) {
            $row = DB::selectOne('
                SELECT product_var.*,
                    product.name AS product_name,
                    product.images,
                    color.name AS color_name,
                    size.name AS size_name
                FROM product_var
                INNER JOIN product ON product.id = product_var.product_id
                LEFT JOIN color ON color.id = product_var.color_id
                LEFT JOIN size ON size.id = product_var.size_id
                WHERE product_var.id = ?
            ', [$varId]);

            if (!$row) {
                continue;
            }

            $lines[] = (object) [
                'var_id' => (int) $varId,
                'product_id' => $row->product_id,
                'name' => $row->product_name,
                'image_url' => $this->productImage($row->images),
                'color_name' => $row->color_name,
                'size_name' => $row->size_name,
                'price' => (int) $row->prices,
                'stock' => (int) $row->stock,
                'quantity' => (int) $qty,
                'line_total' => (int) $row->prices * (int) $qty,
            ];
        }

        return $lines;
    }
}
