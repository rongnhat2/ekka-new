<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\Concerns\LoadsProducts;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use LoadsProducts;

    /**
     * Display products by category.
     */
    public function category(Request $request)
    {
        $categoryId = (int) $request->query('tag', 0);
        $keyword = trim($request->query('keyword', ''));
        $sort = (int) $request->query('sort', 0);

        $query = Product::active();

        if ($categoryId > 0) {
            $query->where('cateID', $categoryId);
        }
        if ($keyword !== '') {
            $query->where('proName', 'like', '%' . $keyword . '%');
        }

        if ($sort === 2) {
            $query->orderBy('proName');
        }

        $products = $query->paginate(12)->appends($request->query());
        $this->attachVariantSummary($products->items());

        $currentCategory = null;
        if ($categoryId > 0) {
            $currentCategory = Category::find($categoryId);
            if ($currentCategory) {
                $currentCategory->id = $currentCategory->cateID;
                $currentCategory->name = $currentCategory->cateName;
            }
        }

        return view('customer.category', compact('products', 'currentCategory', 'categoryId', 'keyword', 'sort'));
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = $this->getProductWithVariants($id);
        if (!$product) {
            abort(404);
        }

        $related = $this->attachVariantSummary(
            Product::active()
                ->where('cateID', $product->cateID)
                ->where('proID', '!=', $product->proID)
                ->limit(4)
                ->get()
        );

        return view('customer.product', compact('product', 'related'));
    }
}
