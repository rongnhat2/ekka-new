<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Session;

class AuthAdmin
{
    public function handle($request, Closure $next, $middleware)
    {
        $token = Session('_token__') ? Session('_token__') : $request->cookie('_token__');
        if ($middleware == 'auth') {
            if ($token) {
                list($user_id, $token) = explode('$', $token, 2);
                $user = Admin::find($user_id);
                if ($user) {
                    $secret_key = $user->secret_key;
                    if ($user->status) {
                        if (Hash::check($user_id . '$' . $secret_key, $token)) {
                            return redirect()->route('admin.index');
                        } else {
                            Cookie::queue(Cookie::forget('_token__'));
                            $request->session()->forget('_token__');
                            return redirect()->route('admin.login')->with('success', 'Token đã hết hạn');
                        }
                    } else {
                        $request->session()->forget('_token__');
                        Cookie::queue(Cookie::forget('_token__'));
                        return redirect()->route('admin.login')->with('error', 'Tài khoản đã bị khóa!');
                    }
                } else {
                    $request->session()->forget('_token__');
                    Cookie::queue(Cookie::forget('_token__'));
                    return redirect()->route('admin.login')->with('success', 'Tài khoản không tồn tại!');
                }
            } else {
                return $next($request);
            }
        } elseif ($middleware == 'preview') {
            if ($token) {
                return $next($request);
            } else {
                return redirect()->route('customer.index');
            }
        } else {
            if ($token) {
                list($user_id, $token) = explode('$', $token, 2);
                $user = Admin::find($user_id);
                if ($user && $user->status) {
                    $secret_key = $user->secret_key;
                    if (Hash::check($user_id . '$' . $secret_key, $token)) {
                        return $next($request);
                    } else {
                        Cookie::queue(Cookie::forget('_token__'));
                        $request->session()->forget('_token__');
                        return redirect()->route('admin.login')->with('success', 'Token đã hết hạn');
                    }
                } else {
                    $request->session()->forget('_token__');
                    Cookie::queue(Cookie::forget('_token__'));
                    return redirect()->route('admin.login')->with($user ? 'error' : 'success', $user ? 'Tài khoản đã bị khóa!' : 'Tài khoản không tồn tại!');
                }
            } else {
                return redirect()->route('admin.login')->with('success', 'Bạn cần đăng nhập để thực hiện hành động này');
            }
        }
    }
}
