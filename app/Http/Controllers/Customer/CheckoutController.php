<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\Concerns\LoadsProducts;
use App\Support\CustomerContext;
use Illuminate\Http\Request;
use DB;

class CheckoutController extends Controller
{
    use LoadsProducts;

    public function index(Request $request)
    {
        $cart = CustomerContext::cart($request);
        if (!$cart) {
            return redirect()->route('customer.view.cart')->with('error', 'Giỏ hàng trống');
        }

        $lines = $this->getCartLines($cart);
        $subtotal = array_sum(array_column($lines, 'line_total'));
        $user = CustomerContext::user($request);

        return view('customer.checkout', compact('lines', 'subtotal', 'user'));
    }

    public function store(Request $request)
    {
        $user = CustomerContext::user($request);
        if (!$user['is_login']) {
            return redirect()->route('customer.view.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $cart = CustomerContext::cart($request);
        $lines = $this->getCartLines($cart);
        if (!$lines) {
            return redirect()->route('customer.view.cart')->with('error', 'Giỏ hàng trống');
        }

        $subtotal = array_sum(array_column($lines, 'line_total'));
        $now = now();

        DB::beginTransaction();
        try {
            DB::table('customer')->where('id', $user['id'])->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'updated_at' => $now,
            ]);

            $orderId = DB::table('orders')->insertGetId([
                'customer_id' => $user['id'],
                'subtotal' => $subtotal,
                'discount' => 0,
                'total' => $subtotal,
                'order_status' => 0,
                'payment_status' => $request->payment_method == 2 ? 2 : 1,
                'order_type' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($lines as $line) {
                if ($line->quantity > $line->stock) {
                    throw new \RuntimeException('Sản phẩm "' . $line->name . '" không đủ tồn kho');
                }

                DB::table('order_detail')->insert([
                    'order_id' => $orderId,
                    'product_id' => $line->product_id,
                    'product_var_id' => $line->var_id,
                    'product_name' => $line->name,
                    'quantity' => $line->quantity,
                    'price' => $line->price,
                    'discount' => 0,
                    'total_price' => $line->line_total,
                    'suborder_status' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $request->session()->forget('cart');

        return redirect()->route('customer.view.profile')->with('success', 'Đặt hàng thành công #' . $orderId);
    }
}
