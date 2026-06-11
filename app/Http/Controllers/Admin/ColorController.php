<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ColorController extends Controller
{
    public function index()
    {
        $colors = DB::select('SELECT * FROM color ORDER BY id DESC');
        return view('admin.color.index', compact('colors'));
    }

    public function store(Request $request)
    {
        DB::insert(
            'INSERT INTO color (name, hex) VALUES (?, ?)',
            [$request->name, $request->hex]
        );
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::update(
            'UPDATE color SET name = ?, hex = ? WHERE id = ?',
            [$request->name, $request->hex, $request->id]
        );
        return redirect()->back();
    }

    public function destroy($id)
    {
        DB::delete('DELETE FROM color WHERE id = ?', [$id]);
        return redirect()->back();
    }
}
