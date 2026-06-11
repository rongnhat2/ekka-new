<?php

namespace App\Http\Middleware;

use App\Support\CustomerContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthCustomer
{
    public function handle(Request $request, Closure $next, $middleware)
    {
        $user = CustomerContext::user($request);

        if ($middleware === 'auth') {
            if ($user['is_login']) {
                return redirect()->route('customer.view.index');
            }
            return $next($request);
        }

        if ($user['is_login']) {
            return $next($request);
        }

        Cookie::queue(Cookie::forget('_token_'));
        return redirect()->route('customer.view.login')->with('error', 'Bạn cần đăng nhập để thực hiện hành động này');
    }
}
