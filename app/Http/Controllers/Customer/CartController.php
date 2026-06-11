<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\Concerns\LoadsProducts;
use App\Support\CustomerContext;
use Illuminate\Http\Request;
use DB;

class CartController extends Controller
{
    use LoadsProducts;

    public function index(Request $request)
    {
        $cart = CustomerContext::cart($request);
        $lines = $this->getCartLines($cart);
        $subtotal = array_sum(array_column($lines, 'line_total'));

        return view('customer.cart', compact('lines', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_var_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $varId = (int) $request->product_var_id;
        $qty = (int) ($request->quantity ?? 1);

        $var = DB::table('product_var')->where('id', $varId)->where('status', 1)->first();
        if (!$var) {
            return redirect()->back()->with('error', 'Biến thể không tồn tại');
        }

        $cart = CustomerContext::cart($request);
        $cart[$varId] = ($cart[$varId] ?? 0) + $qty;
        $request->session()->put('cart', $cart);

        return redirect()->route('customer.view.cart')->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function update(Request $request)
    {
        $quantities = $request->input('quantities', []);
        $cart = [];

        foreach ($quantities as $varId => $qty) {
            $qty = (int) $qty;
            if ($qty > 0) {
                $cart[(int) $varId] = $qty;
            }
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('customer.view.cart')->with('success', 'Đã cập nhật giỏ hàng');
    }

    public function remove(Request $request, $varId)
    {
        $cart = CustomerContext::cart($request);
        unset($cart[(int) $varId]);
        $request->session()->put('cart', $cart);

        return redirect()->route('customer.view.cart');
    }
}
