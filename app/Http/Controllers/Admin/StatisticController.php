<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->query('year', date('Y'));
        $month = (int) $request->query('month', date('n'));
        $year = max(2020, min(2100, $year));
        $month = max(1, min(12, $month));

        $deliveredStatus = Order::STATUS_DELIVERED;

        $summary = [
            'turnover' => (int) Order::where('staValue', $deliveredStatus)->sum('totalPrice'),
            'items_sold' => (int) OrderDetail::whereHas('order', function ($q) use ($deliveredStatus) {
                $q->where('staValue', $deliveredStatus);
            })->sum('quantity'),
            'order_count' => Order::where('staValue', $deliveredStatus)->count(),
            'customer_count' => (int) Order::where('staValue', $deliveredStatus)->distinct('userID')->count('userID'),
        ];

        $bestSellers = OrderDetail::query()
            ->select([
                'orderDetail.proID',
                'product.proName',
                'product.IMG',
                DB::raw('SUM(orderDetail.quantity) AS sold'),
            ])
            ->join('order', 'order.ordID', '=', 'orderDetail.ordID')
            ->join('product', 'product.proID', '=', 'orderDetail.proID')
            ->where('order.staValue', $deliveredStatus)
            ->groupBy('orderDetail.proID', 'product.proName', 'product.IMG')
            ->limit(5)
            ->get()
            ->each(function ($row) {
                $row->stock = (int) ProVariant::where('proID', $row->proID)->sum('stock');
            });

        $worstSellers = OrderDetail::query()
            ->select([
                'orderDetail.proID',
                'product.proName',
                'product.IMG',
                DB::raw('SUM(orderDetail.quantity) AS sold'),
            ])
            ->join('order', 'order.ordID', '=', 'orderDetail.ordID')
            ->join('product', 'product.proID', '=', 'orderDetail.proID')
            ->where('order.staValue', $deliveredStatus)
            ->groupBy('orderDetail.proID', 'product.proName', 'product.IMG')
            ->orderBy('sold')
            ->limit(5)
            ->get()
            ->each(function ($row) {
                $row->stock = (int) ProVariant::where('proID', $row->proID)->sum('stock');
            });

        $lowStock = Product::query()
            ->select([
                'product.proID AS product_id',
                'product.proName AS name',
                'product.IMG AS images',
                DB::raw('COALESCE(SUM(ProVariant.stock), 0) AS stock'),
                DB::raw('COALESCE(MIN(ProVariant.minQuantity), 0) AS min_quantity'),
            ])
            ->join('ProVariant', 'ProVariant.proID', '=', 'product.proID')
            ->groupBy('product.proID', 'product.proName', 'product.IMG')
            ->havingRaw('stock <= min_quantity OR stock <= 10')
            ->orderBy('stock')
            ->limit(10)
            ->get();

        $revenueByDay = $this->fillDailyRevenue($year, $month);
        $revenueByMonth = $this->fillMonthlyRevenue($year);

        return view('admin.statistic.index', compact(
            'summary',
            'bestSellers',
            'worstSellers',
            'lowStock',
            'revenueByDay',
            'revenueByMonth',
            'year',
            'month'
        ));
    }

    private function fillDailyRevenue(int $year, int $month): array
    {
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $rows = Order::query()
            ->selectRaw('DAY(ordDate) AS day, COALESCE(SUM(totalPrice), 0) AS revenue')
            ->where('staValue', Order::STATUS_DELIVERED)
            ->whereYear('ordDate', $year)
            ->whereMonth('ordDate', $month)
            ->groupBy(DB::raw('DAY(ordDate)'))
            ->pluck('revenue', 'day');

        $result = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $result[] = ['label' => (string) $d, 'value' => (int) ($rows[$d] ?? 0)];
        }

        return $result;
    }

    private function fillMonthlyRevenue(int $year): array
    {
        $rows = Order::query()
            ->selectRaw('MONTH(ordDate) AS month, COALESCE(SUM(totalPrice), 0) AS revenue')
            ->where('staValue', Order::STATUS_DELIVERED)
            ->whereYear('ordDate', $year)
            ->groupBy(DB::raw('MONTH(ordDate)'))
            ->pluck('revenue', 'month');

        $labels = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $result[] = ['label' => $labels[$m - 1], 'value' => (int) ($rows[$m] ?? 0)];
        }

        return $result;
    }
}
