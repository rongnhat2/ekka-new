<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\Concerns\LoadsProducts;
use Illuminate\Http\Request;
use DB;

class DisplayController extends Controller
{
    use LoadsProducts;

    public function index()
    {
        $categories = DB::table('category')->where('status', 1)->orderBy('name')->get();
        $activeCategory = $categories->first();

        $categoryProducts = [];
        if ($activeCategory) {
            $categoryProducts = $this->attachVariantSummary(
                DB::table('product')
                    ->where('category_id', $activeCategory->id)
                    ->where('status', 1)
                    ->orderByDesc('id')
                    ->limit(8)
                    ->get()
            );
        }

        $newProducts = $this->attachVariantSummary(
            DB::table('product')->where('status', 1)->orderByDesc('id')->limit(8)->get()
        );

        return view('customer.index', compact('categories', 'activeCategory', 'categoryProducts', 'newProducts'));
    }

    public function login()
    {
        return view('customer.auth');
    }

    public function register()
    {
        return view('customer.register');
    }

    public function forgot()
    {
        return view('customer.forgot');
    }

    public function reset(Request $request)
    {
        return view('customer.reset', [
            'email' => $request->query('email', ''),
        ]);
    }
}
