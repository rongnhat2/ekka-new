<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\SlugTrait;
use App\Http\Controllers\Admin\Traits\BuildsPaginatedListing;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
    use SlugTrait, BuildsPaginatedListing;

    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $perPage = (int) $request->input('per_page', 10);
        $categories = $this->paginateTable($request, 'category', ['name', 'slug', 'id']);

        return view('admin.category.index', compact('categories', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $slug = $this->to_slug($request->name);
        DB::insert(
            'INSERT INTO category (name, slug) VALUES (?, ?)',
            [$request->name, $slug]
        );
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $slug = $this->to_slug($request->name);
        DB::update(
            'UPDATE category SET name = ?, slug = ? WHERE id = ?',
            [$request->name, $slug, $request->id]
        );
        return redirect()->back();
    }

    public function destroy($id)
    {
        DB::delete('DELETE FROM category WHERE id = ?', [$id]);
        return redirect()->back();
    }
}
