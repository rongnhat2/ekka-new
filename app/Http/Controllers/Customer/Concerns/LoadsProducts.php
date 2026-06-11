<?php

namespace App\Http\Controllers\Customer\Concerns;

use App\Models\Product;
use App\Models\ProVariant;

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

    protected function mapProductListItem(Product $product): Product
    {
        $product->id = $product->proID;
        $product->name = $product->proName;
        $product->images = $product->IMG;

        return $product;
    }

    protected function attachVariantSummary($products)
    {
        foreach ($products as $product) {
            if ($product instanceof Product) {
                $this->mapProductListItem($product);
            }
            $product->image_url = $this->productImage($product->images ?? '');
            $proId = $product->proID ?? $product->id;
            $var = ProVariant::where('proID', $proId)
                ->active()
                ->orderBy('price')
                ->first();
            $product->min_price = $var ? (int) $var->price : 0;
        }

        return $products;
    }

    protected function getProductWithVariants($id)
    {
        $product = Product::with([
            'category',
            'brand',
            'proVariants' => function ($query) {
                $query->active()
                    ->with(['color', 'size', 'material'])
                    ->orderBy('proVarID');
            },
        ])->find($id);

        if (!$product) {
            return null;
        }

        $product->id = $product->proID;
        $product->name = $product->proName;
        $product->images = $product->IMG;
        $product->description = $product->proDesc;
        $product->category_name = $product->category->cateName ?? null;
        $product->brand_name = $product->brand->brandName ?? null;
        $product->image_list = $product->image_list;

        $product->variants = $product->proVariants->map(function (ProVariant $var) {
            return (object) [
                'id' => $var->proVarID,
                'prices' => $var->price,
                'stock' => $var->stock,
                'color_name' => $var->color->colorValue ?? null,
                'color_hex' => $var->color->hex ?? null,
                'size_name' => $var->size->sizeValue ?? null,
                'material_name' => $var->material->mateName ?? null,
            ];
        });

        return $product;
    }

    protected function getCartLines(array $cart): array
    {
        $lines = [];

        foreach ($cart as $varId => $qty) {
            $var = ProVariant::with(['product', 'color', 'size'])->find($varId);

            if (!$var || !$var->product) {
                continue;
            }

            $lines[] = (object) [
                'var_id' => (int) $varId,
                'product_id' => $var->product->proID,
                'name' => $var->product->proName,
                'image_url' => $this->productImage($var->product->IMG),
                'color_name' => $var->color->colorValue ?? null,
                'size_name' => $var->size->sizeValue ?? null,
                'price' => (int) $var->price,
                'base_price' => (int) $var->price,
                'sale_price' => (int) $var->price,
                'stock' => (int) $var->stock,
                'quantity' => (int) $qty,
                'line_total' => (int) $var->price * (int) $qty,
            ];
        }

        return $lines;
    }
}
