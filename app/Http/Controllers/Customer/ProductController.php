<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\Concerns\LoadsProducts;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    use LoadsProducts;

    public function category(Request $request)
    {
        $categoryId = (int) $request->query('tag', 0);
        $keyword = trim($request->query('keyword', ''));
        $sort = (int) $request->query('sort', 0);

        $query = DB::table('product')->where('status', 1);

        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }
        if ($keyword !== '') {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        switch ($sort) {
            case 2: $query->orderBy('name'); break;
            case 3: $query->orderByDesc('name'); break;
            default: $query->orderByDesc('id'); break;
        }

        $products = $query->paginate(12)->appends($request->query());
        $this->attachVariantSummary($products->items());
        $currentCategory = $categoryId > 0
            ? DB::table('category')->where('id', $categoryId)->first()
            : null;

        return view('customer.category', compact('products', 'currentCategory', 'categoryId', 'keyword', 'sort'));
    }

    public function show($id)
    {
        $product = $this->getProductWithVariants($id);
        if (!$product) {
            abort(404);
        }

        $related = $this->attachVariantSummary(
            DB::table('product')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 1)
                ->orderByDesc('id')
                ->limit(4)
                ->get()
        );

        return view('customer.product', compact('product', 'related'));
    }
}
