<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProVariant;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductVarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productVars = ProVariant::with(['product', 'color', 'size', 'material'])
            ->get()
            ->each(function (ProVariant $var) {
                $var->product_name = $var->product->proName ?? null;
                $var->color_name = $var->color->colorValue ?? null;
                $var->size_name = $var->size->sizeValue ?? null;
                $var->material_name = $var->material->mateName ?? null;
            });

        $products = Product::active()->orderBy('proName')->get(['proID', 'proName']);
        $colors = Color::orderBy('colorValue')->get(['colorID', 'colorValue']);
        $sizes = Size::orderBy('sizeValue')->get(['sizeID', 'sizeValue']);
        $materials = Material::orderBy('mateName')->get(['mateID', 'mateName']);

        return view('admin.product_var.index', compact(
            'productVars',
            'products',
            'colors',
            'sizes',
            'materials'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'color_id' => 'required|integer',
            'size_id' => 'required|integer',
            'material_id' => 'required|integer',
            'codeSKU' => 'nullable|string|max:100',
            'prices' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'minQuantity' => 'required|integer|min:1',
        ]);

        ProVariant::create([
            'proID' => $request->product_id,
            'colorID' => $request->color_id,
            'sizeID' => $request->size_id,
            'mateID' => $request->material_id,
            'codeSKU' => $request->codeSKU ?? '',
            'price' => $request->prices,
            'stock' => $request->stock,
            'minQuantity' => $request->minQuantity,
        ]);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'product_id' => 'required|integer',
            'color_id' => 'required|integer',
            'size_id' => 'required|integer',
            'material_id' => 'required|integer',
            'codeSKU' => 'nullable|string|max:100',
            'prices' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'minQuantity' => 'required|integer|min:1',
        ]);

        $variant = ProVariant::findOrFail($request->id);
        $variant->update([
            'proID' => $request->product_id,
            'colorID' => $request->color_id,
            'sizeID' => $request->size_id,
            'mateID' => $request->material_id,
            'codeSKU' => $request->codeSKU ?? '',
            'price' => $request->prices,
            'stock' => $request->stock,
            'minQuantity' => $request->minQuantity,
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ProVariant::findOrFail($id)->delete();

        return redirect()->back();
    }
}
