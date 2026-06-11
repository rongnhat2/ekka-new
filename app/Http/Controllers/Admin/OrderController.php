<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class OrderController extends Controller
{
    private $statusLabels = [
        0 => 'Chờ xử lí',
        1 => 'Chưa hoàn thiện',
        2 => 'Đã hoàn thiện',
        3 => 'Đã giao hàng',
        4 => 'Hoàn trả',
    ];

    private $paymentLabels = [
        0 => '',
        1 => 'Chưa thanh toán',
        2 => 'Đã thanh toán',
    ];

    public function index(Request $request)
    {
        $statusParam = $request->query('status');

        if ($statusParam === 'all') {
            $currentStatus = 'all';
        } elseif ($statusParam === null || $statusParam === '') {
            $currentStatus = 0;
        } else {
            $currentStatus = in_array((int) $statusParam, [0, 1, 2, 3, 4], true)
                ? (int) $statusParam
                : 0;
        }

        $query = '
            SELECT orders.*,
                customer.name AS username,
                customer.email,
                customer.phone AS telephone,
                customer.address
            FROM orders
            INNER JOIN customer ON orders.customer_id = customer.id
        ';

        if ($currentStatus !== 'all') {
            $orders = DB::select($query . ' WHERE orders.order_status = ? ORDER BY orders.id DESC', [$currentStatus]);
        } else {
            $orders = DB::select($query . ' ORDER BY orders.id DESC');
        }

        return view('admin.order.index', [
            'orders' => $orders,
            'currentStatus' => $currentStatus,
            'statusLabels' => $this->statusLabels,
            'paymentLabels' => $this->paymentLabels,
        ]);
    }

    public function data($id)
    {
        $order = DB::selectOne('
            SELECT orders.*,
                customer.name AS username,
                customer.email,
                customer.phone AS telephone,
                customer.address
            FROM orders
            INNER JOIN customer ON orders.customer_id = customer.id
            WHERE orders.id = ?
        ', [$id]);

        if (!$order) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $details = DB::select('
            SELECT order_detail.*,
                product_var.stock,
                color.name AS color_name,
                size.name AS size_name,
                material.name AS material_name
            FROM order_detail
            INNER JOIN product_var ON order_detail.product_var_id = product_var.id
            LEFT JOIN color ON product_var.color_id = color.id
            LEFT JOIN size ON product_var.size_id = size.id
            LEFT JOIN material ON product_var.material_id = material.id
            WHERE order_detail.order_id = ?
            ORDER BY order_detail.id ASC
        ', [$id]);

        $dataSub = array_map(function ($row) {
            return [
                'product_id' => $row->product_id,
                'name' => $row->product_name,
                'quantity' => $row->quantity,
                'size_name' => $row->size_name,
                'color_name' => $row->color_name,
                'material_name' => $row->material_name,
                'price' => number_format($row->price),
                'discount' => $row->discount,
                'total_price' => number_format($row->total_price),
                'stock' => $row->stock,
                'suborder_status' => $row->suborder_status,
            ];
        }, $details);

        return response()->json([
            'data' => [
                'data_order' => [[
                    'username' => $order->username,
                    'address' => $order->address,
                    'email' => $order->email,
                    'telephone' => $order->telephone,
                    'order_status' => $order->order_status,
                ]],
                'data_sub' => $dataSub,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'data_id' => 'required|integer',
            'data_status' => 'required|integer|min:0|max:4',
        ]);

        DB::table('orders')->where('id', $request->data_id)->update([
            'order_status' => (int) $request->data_status,
            'updated_at' => now(),
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 200]);
        }

        return redirect()->route('admin.order.index', ['status' => $request->data_status]);
    }

    public function create()
    {
        $products = DB::select('SELECT id, name FROM product WHERE status = 1 ORDER BY name');

        return view('admin.order.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string|max:500',
            'discount' => 'nullable|integer|min:0|max:100',
            'items' => 'required|array|min:1',
        ]);

        $discount = (int) ($request->discount ?? 0);
        $items = $request->input('items', []);
        $now = now();

        DB::beginTransaction();
        try {
            $customerId = DB::table('customer')->where('phone', $request->customer_phone)->value('id');

            if (!$customerId) {
                $customerId = DB::table('customer')->insertGetId([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'email' => $request->customer_email ?: ($request->customer_phone . '@offline.local'),
                    'address' => $request->customer_address ?: 'Mua tại quầy',
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                $customerUpdate = [
                    'name' => $request->customer_name,
                    'updated_at' => $now,
                ];
                if ($request->customer_email) {
                    $customerUpdate['email'] = $request->customer_email;
                }
                if ($request->customer_address) {
                    $customerUpdate['address'] = $request->customer_address;
                }
                DB::table('customer')->where('id', $customerId)->update($customerUpdate);
            }

            $subtotal = 0;
            $lines = [];

            foreach ($items as $item) {
                if (empty($item['product_var_id']) || empty($item['quantity'])) {
                    continue;
                }

                $var = DB::table('product_var')
                    ->join('product', 'product.id', '=', 'product_var.product_id')
                    ->where('product_var.id', $item['product_var_id'])
                    ->select('product_var.*', 'product.name AS product_name')
                    ->first();

                if (!$var) {
                    continue;
                }

                $qty = (int) $item['quantity'];
                if ($qty > $var->stock) {
                    throw new \RuntimeException('Sản phẩm "' . $var->product_name . '" không đủ tồn kho (còn ' . $var->stock . ')');
                }

                $lineTotal = (int) ($var->prices * $qty);
                $subtotal += $lineTotal;

                $lines[] = [
                    'product_id' => $var->product_id,
                    'product_var_id' => $var->id,
                    'product_name' => $var->product_name,
                    'quantity' => $qty,
                    'price' => $var->prices,
                    'discount' => $discount,
                    'total_price' => (int) ($lineTotal * (100 - $discount) / 100),
                    'suborder_status' => 1,
                    'stock_var_id' => $var->id,
                    'stock_qty' => $qty,
                ];
            }

            if (!$lines) {
                throw new \RuntimeException('Vui lòng chọn ít nhất một sản phẩm hợp lệ');
            }

            $total = (int) ($subtotal * (100 - $discount) / 100);

            $orderId = DB::table('orders')->insertGetId([
                'customer_id' => $customerId,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'order_status' => 3,
                'payment_status' => 2,
                'order_type' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($lines as $line) {
                DB::table('order_detail')->insert([
                    'order_id' => $orderId,
                    'product_id' => $line['product_id'],
                    'product_var_id' => $line['product_var_id'],
                    'product_name' => $line['product_name'],
                    'quantity' => $line['quantity'],
                    'price' => $line['price'],
                    'discount' => $line['discount'],
                    'total_price' => $line['total_price'],
                    'suborder_status' => $line['suborder_status'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('product_var')
                    ->where('id', $line['stock_var_id'])
                    ->decrement('stock', $line['stock_qty']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.order.index', ['status' => 'all'])
            ->with('success', 'Đã tạo đơn offline #' . $orderId);
    }
}
