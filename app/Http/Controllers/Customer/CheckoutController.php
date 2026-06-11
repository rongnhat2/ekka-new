<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\Concerns\LoadsProducts;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\ProVariant;
use App\Models\User;
use App\Support\CustomerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    use LoadsProducts;

    /**
     * Display the checkout form.
     */
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

    /**
     * Store a newly created order.
     */
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
        $orderId = null;

        try {
            DB::transaction(function () use ($request, $user, $lines, $subtotal, $now, &$orderId) {
                User::findOrFail($user['id'])->update([
                    'userName' => $request->name,
                    'userPhone' => $request->phone,
                    'userEmail' => $request->email,
                    'userAddress' => $request->address,
                ]);

                $order = Order::create([
                    'userID' => $user['id'],
                    'ordDate' => $now,
                    'ordPhone' => $request->phone,
                    'ordReceiver' => $request->name,
                    'ordAddress' => $request->address,
                    'totalPrice' => $subtotal,
                    'staValue' => 0,
                    'subtotal' => $subtotal,
                    'discount' => 0,
                    'order_type' => Order::TYPE_ONLINE,
                ]);

                Payment::create([
                    'ordID' => $order->ordID,
                    'payDate' => $now,
                    'payStatus' => $request->payment_method == 2 ? Payment::STATUS_PAID : Payment::STATUS_UNPAID,
                    'amount' => $subtotal,
                    'payMethod' => $request->payment_method == 2 ? 'ONLINE' : 'COD',
                ]);

                foreach ($lines as $line) {
                    if ($line->quantity > $line->stock) {
                        throw new \RuntimeException('Sản phẩm "' . $line->name . '" không đủ tồn kho');
                    }

                    OrderDetail::create([
                        'ordID' => $order->ordID,
                        'proID' => $line->product_id,
                        'proVarID' => $line->var_id,
                        'proName' => $line->name,
                        'quantity' => $line->quantity,
                        'basePrice' => $line->base_price,
                        'salePrice' => $line->sale_price,
                        'discount' => 0,
                        'suborder_status' => 0,
                    ]);

                    ProVariant::where('proVarID', $line->var_id)
                        ->decrement('stock', $line->quantity);
                }

                $orderId = $order->ordID;
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $request->session()->forget('cart');

        return redirect()->route('customer.view.profile')->with('success', 'Đặt hàng thành công #' . $orderId);
    }
}
