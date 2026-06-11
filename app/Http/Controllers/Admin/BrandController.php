<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class BrandController extends Controller
{
    public function index()
    {
        $brands = DB::select('SELECT * FROM brand ORDER BY id DESC');
        return view('admin.brand.index', compact('brands'));
    }

    public function store(Request $request)
    {
        DB::insert(
            'INSERT INTO brand (name, description) VALUES (?, ?)',
            [$request->name, $request->description ?? '']
        );
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::update(
            'UPDATE brand SET name = ?, description = ? WHERE id = ?',
            [$request->name, $request->description ?? '', $request->id]
        );
        return redirect()->back();
    }

    public function destroy($id)
    {
        DB::delete('DELETE FROM brand WHERE id = ?', [$id]);
        return redirect()->back();
    }
}
