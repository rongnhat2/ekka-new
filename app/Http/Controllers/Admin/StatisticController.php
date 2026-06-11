<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->query('year', date('Y'));
        $month = (int) $request->query('month', date('n'));
        $year = max(2020, min(2100, $year));
        $month = max(1, min(12, $month));

        $delivered = 'order_status = 3';

        $summary = [
            'turnover' => (int) (DB::selectOne("SELECT COALESCE(SUM(total), 0) AS total FROM orders WHERE {$delivered}")->total ?? 0),
            'items_sold' => (int) (DB::selectOne("
                SELECT COALESCE(SUM(order_detail.quantity), 0) AS total
                FROM order_detail
                INNER JOIN orders ON orders.id = order_detail.order_id
                WHERE orders.{$delivered}
            ")->total ?? 0),
            'order_count' => (int) (DB::selectOne("SELECT COUNT(*) AS total FROM orders WHERE {$delivered}")->total ?? 0),
            'customer_count' => (int) (DB::selectOne("SELECT COUNT(DISTINCT customer_id) AS total FROM orders WHERE {$delivered}")->total ?? 0),
        ];

        $bestSellers = DB::select("
            SELECT order_detail.product_id,
                product.name,
                product.images,
                SUM(order_detail.quantity) AS sold,
                (
                    SELECT COALESCE(SUM(pv.stock), 0)
                    FROM product_var pv
                    WHERE pv.product_id = order_detail.product_id
                ) AS stock
            FROM order_detail
            INNER JOIN orders ON orders.id = order_detail.order_id
            INNER JOIN product ON product.id = order_detail.product_id
            WHERE orders.{$delivered}
            GROUP BY order_detail.product_id, product.name, product.images
            ORDER BY sold DESC
            LIMIT 5
        ");

        $worstSellers = DB::select("
            SELECT order_detail.product_id,
                product.name,
                product.images,
                SUM(order_detail.quantity) AS sold,
                (
                    SELECT COALESCE(SUM(pv.stock), 0)
                    FROM product_var pv
                    WHERE pv.product_id = order_detail.product_id
                ) AS stock
            FROM order_detail
            INNER JOIN orders ON orders.id = order_detail.order_id
            INNER JOIN product ON product.id = order_detail.product_id
            WHERE orders.{$delivered}
            GROUP BY order_detail.product_id, product.name, product.images
            ORDER BY sold ASC
            LIMIT 5
        ");

        $lowStock = DB::select("
            SELECT product.id AS product_id,
                product.name,
                product.images,
                COALESCE(SUM(product_var.stock), 0) AS stock,
                COALESCE(MIN(product_var.minQuantity), 0) AS min_quantity
            FROM product
            INNER JOIN product_var ON product_var.product_id = product.id
            GROUP BY product.id, product.name, product.images
            HAVING stock <= min_quantity OR stock <= 10
            ORDER BY stock ASC
            LIMIT 10
        ");

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
        $rows = DB::select('
            SELECT DAY(created_at) AS day, COALESCE(SUM(total), 0) AS revenue
            FROM orders
            WHERE order_status = 3 AND YEAR(created_at) = ? AND MONTH(created_at) = ?
            GROUP BY DAY(created_at)
        ', [$year, $month]);

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row->day] = (int) $row->revenue;
        }

        $result = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $result[] = ['label' => (string) $d, 'value' => $map[$d] ?? 0];
        }

        return $result;
    }

    private function fillMonthlyRevenue(int $year): array
    {
        $rows = DB::select('
            SELECT MONTH(created_at) AS month, COALESCE(SUM(total), 0) AS revenue
            FROM orders
            WHERE order_status = 3 AND YEAR(created_at) = ?
            GROUP BY MONTH(created_at)
        ', [$year]);

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row->month] = (int) $row->revenue;
        }

        $labels = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $result[] = ['label' => $labels[$m - 1], 'value' => $map[$m] ?? 0];
        }

        return $result;
    }
}
