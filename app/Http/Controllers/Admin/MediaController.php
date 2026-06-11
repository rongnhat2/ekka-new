<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class MediaController extends Controller
{
    public function index()
    {
        $media = DB::table('media')->orderByDesc('id')->get();
        return view('admin.media.index', compact('media'));
    }

    public function list()
    {
        $media = DB::table('media')->orderByDesc('id')->get();
        return response()->json(['data' => $media]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $size = $file->getSize();

        $dir = public_path('uploads/media');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $file->move($dir, $filename);
        $path = 'uploads/media/' . $filename;

        $id = DB::table('media')->insertGetId([
            'name' => $originalName,
            'path' => $path,
            'mime_type' => $mimeType,
            'size' => $size,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $item = DB::table('media')->where('id', $id)->first();

        if ($request->ajax()) {
            return response()->json(['data' => $item]);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $item = DB::table('media')->where('id', $id)->first();
        if ($item) {
            $fullPath = public_path($item->path);
            if (is_file($fullPath)) {
                unlink($fullPath);
            }
            DB::table('media')->where('id', $id)->delete();
        }

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
