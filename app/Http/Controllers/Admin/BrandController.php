<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();

        return view('admin.brand.index', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        Brand::create([
            'brandName' => $request->name,
            'brandDesc' => $request->description ?? '',
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
            'description' => 'nullable|string|max:500',
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->update([
            'brandName' => $request->name,
            'brandDesc' => $request->description ?? '',
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Brand::findOrFail($id)->delete();

        return redirect()->back();
    }
}
