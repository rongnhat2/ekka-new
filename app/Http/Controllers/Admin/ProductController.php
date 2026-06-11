<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\SlugTrait;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    use SlugTrait;

    public function index()
    {
        $products = DB::select('
            SELECT product.*, category.name AS category_name, brand.name AS brand_name
            FROM product
            LEFT JOIN category ON product.category_id = category.id
            LEFT JOIN brand ON product.brand_id = brand.id
            ORDER BY product.id DESC
        ');

        foreach ($products as $product) {
            $product->variants = DB::select('
                SELECT product_var.*,
                    color.name AS color_name,
                    size.name AS size_name,
                    material.name AS material_name
                FROM product_var
                LEFT JOIN color ON product_var.color_id = color.id
                LEFT JOIN size ON product_var.size_id = size.id
                LEFT JOIN material ON product_var.material_id = material.id
                WHERE product_var.product_id = ?
                ORDER BY product_var.id ASC
            ', [$product->id]);
        }

        $categories = DB::select('SELECT id, name FROM category WHERE status = 1 ORDER BY name');
        $brands = DB::select('SELECT id, name FROM brand WHERE status = 1 ORDER BY name');
        $colors = DB::select('SELECT id, name FROM color WHERE status = 1 ORDER BY name');
        $sizes = DB::select('SELECT id, name FROM size WHERE status = 1 ORDER BY name');
        $materials = DB::select('SELECT id, name FROM material WHERE status = 1 ORDER BY name');

        return view('admin.product.index', compact(
            'products',
            'categories',
            'brands',
            'colors',
            'sizes',
            'materials'
        ));
    }

    public function data($id)
    {
        $product = DB::table('product')->where('id', $id)->first();
        if (!$product) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $variants = DB::table('product_var')->where('product_id', $id)->orderBy('id')->get();

        return response()->json([
            'data' => [
                'product' => $product,
                'variants' => $variants,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $slug = $this->to_slug($request->name);
        $images = trim($request->images ?? '') ?: '[]';

        DB::beginTransaction();
        try {
            $productId = DB::table('product')->insertGetId([
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'slug' => $slug,
                'images' => $images,
                'banner' => $request->banner,
                'description' => $request->description,
                'detail' => $request->detail,
                'status' => $request->status ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->syncVariants($productId, $request->input('variants', []));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $slug = $this->to_slug($request->name);
        $images = trim($request->images ?? '') ?: '[]';

        DB::beginTransaction();
        try {
            DB::table('product')->where('id', $request->id)->update([
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'slug' => $slug,
                'images' => $images,
                'banner' => $request->banner,
                'description' => $request->description,
                'detail' => $request->detail,
                'status' => $request->status ?? 1,
                'updated_at' => now(),
            ]);

            $this->syncVariants($request->id, $request->input('variants', []));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        DB::delete('DELETE FROM product_var WHERE product_id = ?', [$id]);
        DB::delete('DELETE FROM product WHERE id = ?', [$id]);
        return redirect()->back();
    }

    private function syncVariants($productId, array $variants)
    {
        $keepIds = [];

        foreach ($variants as $variant) {
            if (empty($variant['color_id']) || empty($variant['size_id']) || empty($variant['material_id'])) {
                continue;
            }

            $payload = [
                'color_id' => $variant['color_id'],
                'size_id' => $variant['size_id'],
                'material_id' => $variant['material_id'],
                'codeSKU' => $variant['codeSKU'],
                'prices' => $variant['prices'],
                'minQuantity' => $variant['minQuantity'] ?? 1,
                'status' => 1,
                'updated_at' => now(),
            ];

            if (!empty($variant['id'])) {
                DB::table('product_var')->where('id', $variant['id'])->update($payload);
                $keepIds[] = $variant['id'];
            } else {
                $payload['product_id'] = $productId;
                $payload['stock'] = 0;
                $payload['created_at'] = now();
                $keepIds[] = DB::table('product_var')->insertGetId($payload);
            }
        }

        $query = DB::table('product_var')->where('product_id', $productId);
        if (count($keepIds)) {
            $query->whereNotIn('id', $keepIds);
        }
        $query->delete();
    }
}
