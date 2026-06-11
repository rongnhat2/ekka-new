<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\Concerns\LoadsProducts;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    use LoadsProducts;

    /**
     * Display the storefront home page.
     */
    public function index()
    {
        $categories = Category::active()
            ->orderBy('cateName')
            ->get()
            ->each(function (Category $category) {
                $category->id = $category->cateID;
                $category->name = $category->cateName;
            });

        $activeCategory = $categories->first();

        $categoryProducts = [];
        if ($activeCategory) {
            $categoryProducts = $this->attachVariantSummary(
                Product::active()
                    ->where('cateID', $activeCategory->cateID)
                    ->limit(8)
                    ->get()
            );
        }

        $newProducts = $this->attachVariantSummary(
            Product::active()
                ->limit(8)
                ->get()
        );

        return view('customer.index', compact('categories', 'activeCategory', 'categoryProducts', 'newProducts'));
    }

    /**
     * Show the login form.
     */
    public function login()
    {
        return view('customer.auth');
    }

    /**
     * Show the registration form.
     */
    public function register()
    {
        return view('customer.register');
    }

    /**
     * Show the forgot password form.
     */
    public function forgot()
    {
        return view('customer.forgot');
    }

    /**
     * Show the reset password form.
     */
    public function reset(Request $request)
    {
        return view('customer.reset', [
            'email' => $request->query('email', ''),
        ]);
    }
}
