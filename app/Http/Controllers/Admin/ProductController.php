<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\SlugTrait;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use SlugTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with([
            'category',
            'brand',
            'proVariants.color',
            'proVariants.size',
            'proVariants.material',
        ])->get()->each(function (Product $product) {
            $product->category_name = $product->category->cateName ?? null;
            $product->brand_name = $product->brand->brandName ?? null;
            $product->variants = $product->proVariants->each(function (ProVariant $variant) {
                $variant->color_name = $variant->color->colorValue ?? null;
                $variant->size_name = $variant->size->sizeValue ?? null;
                $variant->material_name = $variant->material->mateName ?? null;
            });
        });

        $categories = Category::active()->orderBy('cateName')->get(['cateID', 'cateName']);
        $brands = Brand::orderBy('brandName')->get(['brandID', 'brandName']);
        $colors = Color::orderBy('colorValue')->get(['colorID', 'colorValue']);
        $sizes = Size::orderBy('sizeValue')->get(['sizeID', 'sizeValue']);
        $materials = Material::orderBy('mateName')->get(['mateID', 'mateName']);

        return view('admin.product.index', compact(
            'products',
            'categories',
            'brands',
            'colors',
            'sizes',
            'materials'
        ));
    }

    /**
     * Display the specified resource (JSON for edit form).
     */
    public function data($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $variants = ProVariant::where('proID', $id)->orderBy('proVarID')->get();

        return response()->json([
            'data' => [
                'product' => $product,
                'variants' => $variants,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::create([
                'cateID' => $request->category_id,
                'brandID' => $request->brand_id,
                'proName' => $request->name,
                'slug' => $this->to_slug($request->name),
                'IMG' => trim($request->images ?? '') ?: '[]',
                'banner' => $request->banner,
                'proDesc' => $request->description,
                'detail' => $request->detail,
                'status' => $request->status ?? 1,
            ]);

            $this->syncVariants($product->proID, $request->input('variants', []));
        });

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::findOrFail($request->id);
            $product->update([
                'cateID' => $request->category_id,
                'brandID' => $request->brand_id,
                'proName' => $request->name,
                'slug' => $this->to_slug($request->name),
                'IMG' => trim($request->images ?? '') ?: '[]',
                'banner' => $request->banner,
                'proDesc' => $request->description,
                'detail' => $request->detail,
                'status' => $request->status ?? 1,
            ]);

            $this->syncVariants($product->proID, $request->input('variants', []));
        });

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->proVariants()->delete();
        $product->delete();

        return redirect()->back();
    }

    private function syncVariants(int $productId, array $variants): void
    {
        $keepIds = [];

        foreach ($variants as $variant) {
            if (empty($variant['color_id']) || empty($variant['size_id']) || empty($variant['material_id'])) {
                continue;
            }

            $payload = [
                'colorID' => $variant['color_id'],
                'sizeID' => $variant['size_id'],
                'mateID' => $variant['material_id'],
                'codeSKU' => $variant['codeSKU'],
                'price' => $variant['prices'],
                'minQuantity' => $variant['minQuantity'] ?? 1,
            ];

            if (!empty($variant['id'])) {
                ProVariant::where('proVarID', $variant['id'])->update($payload);
                $keepIds[] = (int) $variant['id'];
            } else {
                $created = ProVariant::create(array_merge($payload, [
                    'proID' => $productId,
                    'stock' => 0,
                ]));
                $keepIds[] = $created->proVarID;
            }
        }

        $query = ProVariant::where('proID', $productId);
        if ($keepIds) {
            $query->whereNotIn('proVarID', $keepIds);
        }
        $query->delete();
    }
}
