<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\BuildsPaginatedListing;
use App\Http\Controllers\Admin\Traits\SlugTrait;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use SlugTrait, BuildsPaginatedListing;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $perPage = (int) $request->input('per_page', 10);
        $categories = $this->paginateModel(
            $request,
            Category::class,
            ['cateName', 'slug', 'cateID'],
            'cateID',
            'desc'
        );

        return view('admin.category.index', compact('categories', 'search', 'perPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'cateName' => $request->name,
            'slug' => $this->to_slug($request->name),
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
        ]);

        $category = Category::findOrFail($request->id);
        $category->update([
            'cateName' => $request->name,
            'slug' => $this->to_slug($request->name),
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return redirect()->back();
    }
}
