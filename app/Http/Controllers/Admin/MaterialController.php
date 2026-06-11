<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = DB::select('SELECT * FROM material ORDER BY id DESC');
        return view('admin.material.index', compact('materials'));
    }

    public function store(Request $request)
    {
        DB::insert('INSERT INTO material (name) VALUES (?)', [$request->name]);
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::update(
            'UPDATE material SET name = ? WHERE id = ?',
            [$request->name, $request->id]
        );
        return redirect()->back();
    }

    public function destroy($id)
    {
        DB::delete('DELETE FROM material WHERE id = ?', [$id]);
        return redirect()->back();
    }
}
