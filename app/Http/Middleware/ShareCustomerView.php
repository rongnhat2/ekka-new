<?php

namespace App\Http\Middleware;

use App\Support\CustomerContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShareCustomerView
{
    public function handle(Request $request, Closure $next)
    {
        view()->share('customer_data', CustomerContext::user($request));
        view()->share('cart_count', CustomerContext::cartCount($request));
        view()->share('nav_categories', DB::table('category')->where('status', 1)->orderBy('name')->get());

        return $next($request);
    }
}
