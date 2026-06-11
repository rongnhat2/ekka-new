<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Support\CustomerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = CustomerContext::user($request);
        $orders = DB::select('
            SELECT orders.*
            FROM orders
            WHERE customer_id = ?
            ORDER BY id DESC
        ', [$user['id']]);

        return view('customer.profile', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = CustomerContext::user($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        DB::table('customer')->where('id', $user['id'])->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address ?? '',
            'updated_at' => now(),
        ]);

        return redirect()->route('customer.view.profile')->with('success', 'Đã cập nhật thông tin');
    }

    public function orderDetail(Request $request, $id)
    {
        $user = CustomerContext::user($request);
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('customer_id', $user['id'])
            ->first();

        if (!$order) {
            abort(404);
        }

        $details = DB::select('
            SELECT order_detail.*,
                color.name AS color_name,
                size.name AS size_name
            FROM order_detail
            INNER JOIN product_var ON product_var.id = order_detail.product_var_id
            LEFT JOIN color ON color.id = product_var.color_id
            LEFT JOIN size ON size.id = product_var.size_id
            WHERE order_detail.order_id = ?
        ', [$id]);

        return view('customer.order-detail', compact('order', 'details'));
    }
}
