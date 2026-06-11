<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    /**
     * Display a listing of the resource.
     */
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

        $query = Order::with(['user', 'latestPayment']);
        if ($currentStatus !== 'all') {
            $query->where('staValue', $currentStatus);
        }

        $orders = $query->get()->each(function (Order $order) {
            $order->username = $order->user->userName ?? '';
            $order->email = $order->user->userEmail ?? '';
            $order->telephone = $order->user->userPhone ?? '';
            $order->address = $order->user->userAddress ?? '';
            $order->payment_status = $order->latestPayment->payStatus ?? 0;
        });

        return view('admin.order.index', [
            'orders' => $orders,
            'currentStatus' => $currentStatus,
            'statusLabels' => $this->statusLabels,
            'paymentLabels' => $this->paymentLabels,
        ]);
    }

    /**
     * Display the specified resource (JSON).
     */
    public function data($id)
    {
        $order = Order::with(['user', 'latestPayment'])->find($id);
        if (!$order) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $details = OrderDetail::with(['proVariant.color', 'proVariant.size', 'proVariant.material'])
            ->where('ordID', $id)
            ->orderBy('ordDetailID')
            ->get();

        $dataSub = $details->map(function (OrderDetail $row) {
            return [
                'product_id' => $row->proID,
                'name' => $row->proName,
                'quantity' => $row->quantity,
                'size_name' => $row->proVariant->size->sizeValue ?? null,
                'color_name' => $row->proVariant->color->colorValue ?? null,
                'material_name' => $row->proVariant->material->mateName ?? null,
                'price' => number_format($row->salePrice),
                'discount' => $row->discount,
                'total_price' => number_format($row->salePrice * $row->quantity),
                'stock' => $row->proVariant->stock ?? 0,
                'suborder_status' => $row->suborder_status,
            ];
        })->all();

        return response()->json([
            'data' => [
                'data_order' => [[
                    'username' => $order->user->userName ?? '',
                    'address' => $order->user->userAddress ?? '',
                    'email' => $order->user->userEmail ?? '',
                    'telephone' => $order->user->userPhone ?? '',
                    'order_status' => $order->staValue,
                    'payment_status' => $order->latestPayment->payStatus ?? 0,
                ]],
                'data_sub' => $dataSub,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'data_id' => 'required|integer',
            'data_status' => 'required|integer|min:0|max:4',
        ]);

        $order = Order::findOrFail($request->data_id);
        $order->update(['staValue' => (int) $request->data_status]);

        if ($request->ajax()) {
            return response()->json(['message' => 200]);
        }

        return redirect()->route('admin.order.index', ['status' => $request->data_status]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::active()->orderBy('proName')->get(['proID', 'proName']);

        return view('admin.order.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
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
        $orderId = null;

        try {
            DB::transaction(function () use ($request, $discount, $items, $now, &$orderId) {
                $user = User::where('userPhone', $request->customer_phone)->first();

                if (!$user) {
                    $user = User::create([
                        'userName' => $request->customer_name,
                        'userPhone' => $request->customer_phone,
                        'userEmail' => $request->customer_email ?: ($request->customer_phone . '@offline.local'),
                        'userAddress' => $request->customer_address ?: 'Mua tại quầy',
                        'userPass' => bcrypt(Str::random(16)),
                        'secret_key' => random_int(1000000, 9999999),
                    ]);
                } else {
                    $user->update(array_filter([
                        'userName' => $request->customer_name,
                        'userEmail' => $request->customer_email ?: $user->userEmail,
                        'userAddress' => $request->customer_address ?: $user->userAddress,
                    ]));
                }

                $subtotal = 0;
                $lines = [];

                foreach ($items as $item) {
                    if (empty($item['product_var_id']) || empty($item['quantity'])) {
                        continue;
                    }

                    $variant = ProVariant::with('product')
                        ->find($item['product_var_id']);

                    if (!$variant) {
                        continue;
                    }

                    $qty = (int) $item['quantity'];
                    if ($qty > $variant->stock) {
                        throw new \RuntimeException(
                            'Sản phẩm "' . $variant->product->proName . '" không đủ tồn kho (còn ' . $variant->stock . ')'
                        );
                    }

                    $lineTotal = (int) ($variant->price * $qty);
                    $subtotal += $lineTotal;

                    $lines[] = [
                        'proID' => $variant->proID,
                        'proVarID' => $variant->proVarID,
                        'proName' => $variant->product->proName,
                        'quantity' => $qty,
                        'salePrice' => $variant->price,
                        'discount' => $discount,
                        'suborder_status' => 1,
                        'stock_var_id' => $variant->proVarID,
                        'stock_qty' => $qty,
                    ];
                }

                if (!$lines) {
                    throw new \RuntimeException('Vui lòng chọn ít nhất một sản phẩm hợp lệ');
                }

                $total = (int) ($subtotal * (100 - $discount) / 100);

                $order = Order::create([
                    'userID' => $user->userID,
                    'ordDate' => $now,
                    'ordPhone' => $request->customer_phone,
                    'ordReceiver' => $request->customer_name,
                    'ordAddress' => $request->customer_address ?: 'Mua tại quầy',
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'totalPrice' => $total,
                    'staValue' => 3,
                    'order_type' => 1,
                ]);

                $orderId = $order->ordID;

                foreach ($lines as $line) {
                    OrderDetail::create([
                        'ordID' => $orderId,
                        'proID' => $line['proID'],
                        'proVarID' => $line['proVarID'],
                        'proName' => $line['proName'],
                        'quantity' => $line['quantity'],
                        'basePrice' => $line['salePrice'],
                        'salePrice' => (int) ($line['salePrice'] * (100 - $discount) / 100),
                        'discount' => $line['discount'],
                        'suborder_status' => $line['suborder_status'],
                    ]);

                    ProVariant::where('proVarID', $line['stock_var_id'])
                        ->decrement('stock', $line['stock_qty']);
                }

                Payment::create([
                    'ordID' => $orderId,
                    'payDate' => $now,
                    'payStatus' => 2,
                    'amount' => $total,
                    'payMethod' => 'OFFLINE',
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.order.index', ['status' => 'all'])
            ->with('success', 'Đã tạo đơn offline #' . $orderId);
    }
}
