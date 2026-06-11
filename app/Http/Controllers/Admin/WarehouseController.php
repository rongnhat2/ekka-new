<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $stockItems = DB::select('
            SELECT p.id AS product_id, p.name,
                SUM(pv.stock) AS quantity,
                MIN(pv.prices) AS prices
            FROM product p
            INNER JOIN product_var pv ON pv.product_id = p.id
            GROUP BY p.id, p.name
            ORDER BY p.id DESC
        ');

        $products = DB::select('SELECT id, name FROM product WHERE status = 1 ORDER BY name');

        return view('admin.warehouse.index', compact('stockItems', 'products'));
    }

    public function variants($productId)
    {
        $variants = DB::select('
            SELECT product_var.id,
                product_var.prices,
                product_var.stock,
                color.name AS color_name,
                size.name AS size_name,
                material.name AS material_name
            FROM product_var
            LEFT JOIN color ON product_var.color_id = color.id
            LEFT JOIN size ON product_var.size_id = size.id
            LEFT JOIN material ON product_var.material_id = material.id
            WHERE product_var.product_id = ?
            ORDER BY product_var.id ASC
        ', [$productId]);

        return response()->json(['data' => $variants]);
    }

    public function store(Request $request)
    {
        $adminId = $this->currentAdminId($request);
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $items = $request->input('items', []);

        DB::beginTransaction();
        try {
            $importId = DB::table('warehouse_import')->insertGetId([
                'admin_id' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($items as $item) {
                if (empty($item['product_var_id']) || empty($item['quantity'])) {
                    continue;
                }

                $quantity = (int) $item['quantity'];
                $price = (int) ($item['price'] ?? 0);

                DB::table('warehouse_import_detail')->insert([
                    'import_id' => $importId,
                    'product_var_id' => $item['product_var_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('product_var')
                    ->where('id', $item['product_var_id'])
                    ->increment('stock', $quantity);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('admin.warehouse.index');
    }

    public function history()
    {
        $imports = DB::select('
            SELECT warehouse_import.*,
                admin.email,
                COALESCE((
                    SELECT SUM(warehouse_import_detail.quantity * warehouse_import_detail.price)
                    FROM warehouse_import_detail
                    WHERE warehouse_import_detail.import_id = warehouse_import.id
                ), 0) AS total_price
            FROM warehouse_import
            LEFT JOIN admin ON warehouse_import.admin_id = admin.id
            ORDER BY warehouse_import.id DESC
        ');

        return view('admin.warehouse.history', compact('imports'));
    }

    public function show($id)
    {
        $import = DB::selectOne('
            SELECT warehouse_import.*, admin.email
            FROM warehouse_import
            LEFT JOIN admin ON warehouse_import.admin_id = admin.id
            WHERE warehouse_import.id = ?
        ', [$id]);

        if (!$import) {
            abort(404);
        }

        $details = DB::select('
            SELECT warehouse_import_detail.*,
                product.name AS product_name,
                color.name AS color_name,
                size.name AS size_name,
                material.name AS material_name
            FROM warehouse_import_detail
            INNER JOIN product_var ON warehouse_import_detail.product_var_id = product_var.id
            INNER JOIN product ON product_var.product_id = product.id
            LEFT JOIN color ON product_var.color_id = color.id
            LEFT JOIN size ON product_var.size_id = size.id
            LEFT JOIN material ON product_var.material_id = material.id
            WHERE warehouse_import_detail.import_id = ?
            ORDER BY warehouse_import_detail.id ASC
        ', [$id]);

        return view('admin.warehouse.show', compact('import', 'details'));
    }

    private function currentAdminId(Request $request)
    {
        $token = session('_token__') ?: $request->cookie('_token__');
        if (!$token) {
            return null;
        }

        return (int) explode('$', $token, 2)[0];
    }
}
