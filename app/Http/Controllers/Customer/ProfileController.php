<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Support\CustomerContext;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the user profile.
     */
    public function index(Request $request)
    {
        $user = CustomerContext::user($request);
        $orders = Order::where('userID', $user['id'])
            ->get()
            ->map(function (Order $order) {
                return (object) [
                    'id' => $order->ordID,
                    'ordDate' => $order->ordDate,
                    'total' => $order->totalPrice,
                    'order_status' => $order->staValue,
                    'created_at' => $order->created_at,
                ];
            });

        return view('customer.profile', compact('user', 'orders'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        $user = CustomerContext::user($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        User::findOrFail($user['id'])->update([
            'userName' => $request->name,
            'userPhone' => $request->phone,
            'userAddress' => $request->address ?? '',
        ]);

        return redirect()->route('customer.view.profile')->with('success', 'Đã cập nhật thông tin');
    }

    /**
     * Display the specified order.
     */
    public function orderDetail(Request $request, $id)
    {
        $user = CustomerContext::user($request);
        $orderModel = Order::where('ordID', $id)
            ->where('userID', $user['id'])
            ->firstOrFail();

        $order = (object) [
            'id' => $orderModel->ordID,
            'order_status' => $orderModel->staValue,
            'total' => $orderModel->totalPrice,
            'ordDate' => $orderModel->ordDate,
            'ordPhone' => $orderModel->ordPhone,
            'ordReceiver' => $orderModel->ordReceiver,
            'ordAddress' => $orderModel->ordAddress,
        ];

        $details = OrderDetail::with(['proVariant.color', 'proVariant.size'])
            ->where('ordID', $id)
            ->get()
            ->map(function (OrderDetail $detail) {
                return (object) [
                    'proName' => $detail->proName,
                    'quantity' => $detail->quantity,
                    'price' => $detail->salePrice,
                    'total_price' => $detail->salePrice * $detail->quantity,
                    'color_name' => $detail->proVariant->color->colorValue ?? null,
                    'size_name' => $detail->proVariant->size->sizeValue ?? null,
                ];
            });

        return view('customer.order-detail', compact('order', 'details'));
    }
}
