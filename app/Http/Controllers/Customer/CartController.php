<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\Concerns\LoadsProducts;
use App\Models\ProVariant;
use App\Support\CustomerContext;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use LoadsProducts;

    /**
     * Display the shopping cart.
     */
    public function index(Request $request)
    {
        $cart = CustomerContext::cart($request);
        $lines = $this->getCartLines($cart);
        $subtotal = array_sum(array_column($lines, 'line_total'));

        return view('customer.cart', compact('lines', 'subtotal'));
    }

    /**
     * Add an item to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_var_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $varId = (int) $request->product_var_id;
        $qty = (int) ($request->quantity ?? 1);

        $var = ProVariant::active()->find($varId);
        if (!$var) {
            return redirect()->back()->with('error', 'Biến thể không tồn tại');
        }

        $cart = CustomerContext::cart($request);
        $cart[$varId] = ($cart[$varId] ?? 0) + $qty;
        $request->session()->put('cart', $cart);

        return redirect()->route('customer.view.cart')->with('success', 'Đã thêm vào giỏ hàng');
    }

    /**
     * Update cart quantities.
     */
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

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request, $varId)
    {
        $cart = CustomerContext::cart($request);
        unset($cart[(int) $varId]);
        $request->session()->put('cart', $cart);

        return redirect()->route('customer.view.cart');
    }
}
