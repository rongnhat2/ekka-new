<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = DB::select('SELECT * FROM size ORDER BY id DESC');
        return view('admin.size.index', compact('sizes'));
    }

    public function store(Request $request)
    {
        DB::insert('INSERT INTO size (name) VALUES (?)', [$request->name]);
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::update(
            'UPDATE size SET name = ? WHERE id = ?',
            [$request->name, $request->id]
        );
        return redirect()->back();
    }

    public function destroy($id)
    {
        DB::delete('DELETE FROM size WHERE id = ?', [$id]);
        return redirect()->back();
    }
}
