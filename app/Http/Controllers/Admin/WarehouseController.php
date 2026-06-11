<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProVariant;
use App\Models\StockImport;
use App\Models\StockImportDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockItems = Product::has('proVariants')
            ->with('proVariants:proID,stock,price')
            ->get(['proID', 'proName'])
            ->map(function (Product $product) {
                return (object) [
                    'product_id' => $product->proID,
                    'name' => $product->proName,
                    'quantity' => (int) $product->proVariants->sum('stock'),
                    'prices' => (int) $product->proVariants->min('price'),
                ];
            });

        $products = Product::active()->orderBy('proName')->get(['proID', 'proName']);

        return view('admin.warehouse.index', compact('stockItems', 'products'));
    }

    public function variants($productId)
    {
        $variants = ProVariant::with(['color', 'size', 'material'])
            ->where('proID', $productId)
            ->orderBy('proVarID')
            ->get()
            ->map(function (ProVariant $variant) {
                return (object) [
                    'id' => $variant->proVarID,
                    'prices' => $variant->price,
                    'stock' => $variant->stock,
                    'color_name' => $variant->color->colorValue ?? null,
                    'size_name' => $variant->size->sizeValue ?? null,
                    'material_name' => $variant->material->mateName ?? null,
                ];
            });

        return response()->json(['data' => $variants]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $adminId = $this->currentAdminId($request);
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $items = $request->input('items', []);

        DB::transaction(function () use ($adminId, $items, $request) {
            $import = StockImport::create([
                'adminID' => $adminId,
                'improtDate' => now(),
                'totalQuantity' => 0,
                'note' => $request->input('note'),
            ]);

            $totalQuantity = 0;
            foreach ($items as $item) {
                if (empty($item['product_var_id']) || empty($item['quantity'])) {
                    continue;
                }

                $quantity = (int) $item['quantity'];
                $totalQuantity += $quantity;

                StockImportDetail::create([
                    'importID' => $import->importID,
                    'proVarID' => $item['product_var_id'],
                    'Quantity' => $quantity,
                    'unitPrice' => (int) ($item['price'] ?? 0),
                ]);

                ProVariant::where('proVarID', $item['product_var_id'])
                    ->increment('stock', $quantity);
            }

            $import->update(['totalQuantity' => $totalQuantity]);
        });

        return redirect()->route('admin.warehouse.index');
    }

    public function history()
    {
        $imports = StockImport::with(['admin', 'details'])
            ->get()
            ->each(function (StockImport $import) {
                $import->adminEmail = $import->admin->adminEmail ?? null;
                $import->total_price = (int) $import->details->sum(function (StockImportDetail $detail) {
                    return $detail->Quantity * $detail->unitPrice;
                });
            });

        return view('admin.warehouse.history', compact('imports'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $import = StockImport::with('admin')->find($id);
        if (!$import) {
            abort(404);
        }
        $import->adminEmail = $import->admin->adminEmail ?? null;

        $details = StockImportDetail::with(['proVariant.product', 'proVariant.color', 'proVariant.size', 'proVariant.material'])
            ->where('importID', $id)
            ->orderBy('importDetailID')
            ->get()
            ->each(function (StockImportDetail $detail) {
                $variant = $detail->proVariant;
                $detail->product_name = $variant->product->proName ?? null;
                $detail->color_name = $variant->color->colorValue ?? null;
                $detail->size_name = $variant->size->sizeValue ?? null;
                $detail->material_name = $variant->material->mateName ?? null;
            });

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
