<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $media = Media::all();

        return view('admin.media.index', compact('media'));
    }

    /**
     * Return media list as JSON.
     */
    public function list()
    {
        $media = Media::all();

        return response()->json(['data' => $media]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('file');
        $dir = public_path('uploads/media');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $file->move($dir, $filename);
        $path = 'uploads/media/' . $filename;

        $item = Media::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        if ($request->ajax()) {
            return response()->json(['data' => $item]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = Media::find($id);
        if ($item) {
            $fullPath = public_path($item->path);
            if (is_file($fullPath)) {
                unlink($fullPath);
            }
            $item->delete();
        }

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
