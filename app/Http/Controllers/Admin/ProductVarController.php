<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ProductVarController extends Controller
{
    public function index()
    {
        $productVars = DB::select('
            SELECT product_var.*,
                product.name AS product_name,
                color.name AS color_name,
                size.name AS size_name,
                material.name AS material_name
            FROM product_var
            LEFT JOIN product ON product_var.product_id = product.id
            LEFT JOIN color ON product_var.color_id = color.id
            LEFT JOIN size ON product_var.size_id = size.id
            LEFT JOIN material ON product_var.material_id = material.id
            ORDER BY product_var.id DESC
        ');
        $products = DB::select('SELECT id, name FROM product WHERE status = 1 ORDER BY name');
        $colors = DB::select('SELECT id, name FROM color WHERE status = 1 ORDER BY name');
        $sizes = DB::select('SELECT id, name FROM size WHERE status = 1 ORDER BY name');
        $materials = DB::select('SELECT id, name FROM material WHERE status = 1 ORDER BY name');
        return view('admin.product_var.index', compact(
            'productVars',
            'products',
            'colors',
            'sizes',
            'materials'
        ));
    }

    public function store(Request $request)
    {
        DB::insert(
            'INSERT INTO product_var (product_id, color_id, size_id, material_id, codeSKU, prices, stock, minQuantity)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->product_id,
                $request->color_id,
                $request->size_id,
                $request->material_id,
                $request->codeSKU,
                $request->prices,
                $request->stock,
                $request->minQuantity,
            ]
        );
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::update(
            'UPDATE product_var
             SET product_id = ?, color_id = ?, size_id = ?, material_id = ?,
                 codeSKU = ?, prices = ?, stock = ?, minQuantity = ?
             WHERE id = ?',
            [
                $request->product_id,
                $request->color_id,
                $request->size_id,
                $request->material_id,
                $request->codeSKU,
                $request->prices,
                $request->stock,
                $request->minQuantity,
                $request->id,
            ]
        );
        return redirect()->back();
    }

    public function destroy($id)
    {
        DB::delete('DELETE FROM product_var WHERE id = ?', [$id]);
        return redirect()->back();
    }
}
