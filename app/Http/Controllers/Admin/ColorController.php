<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = Color::all();

        return view('admin.color.index', compact('colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hex' => 'required|string|max:20',
        ]);

        Color::create([
            'colorValue' => $request->name,
            'hex' => $request->hex,
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
            'name' => 'required|string|max:255',
            'hex' => 'required|string|max:20',
        ]);

        $color = Color::findOrFail($request->id);
        $color->update([
            'colorValue' => $request->name,
            'hex' => $request->hex,
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Color::findOrFail($id)->delete();

        return redirect()->back();
    }
}
