<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    //
    public function index()
    {
        // ví dụ dữ liệu đã được lấy ra từ database 
        $data1 = [
            'title' => 'Trang chủ',
            'description' => 'Trang chủ của website',
            'keywords' => 'Trang chủ, website',
            'author' => 'Trang chủ',
            'image' => 'Trang chủ',
            'url' => 'Trang chủ',
        ];
        return view('trang-chu', compact('data1'));
    }
}
