<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = DB::select('SELECT * FROM customer ORDER BY id DESC');

        return view('admin.customer.index', compact('customers'));
    }
}
