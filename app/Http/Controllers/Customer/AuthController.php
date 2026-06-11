<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Support\CustomerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if (DB::table('customer')->where('email', $request->email)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Email đã tồn tại');
        }

        $secretKey = random_int(1000000, 9999999);
        $id = DB::table('customer')->insertGetId([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address ?? '',
            'password' => Hash::make($request->password),
            'secret_key' => $secretKey,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Cookie::queue(Cookie::forget('_token_'));
        Cookie::queue('_token_', CustomerContext::createToken($id), 60 * 24 * 30);

        return redirect()->route('customer.view.profile')->with('success', 'Đăng ký thành công');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = DB::table('customer')
            ->where('email', $request->email)
            ->whereNotNull('password')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error_login', 'Email hoặc mật khẩu không đúng');
        }

        Cookie::queue(Cookie::forget('_token_'));
        Cookie::queue('_token_', CustomerContext::createToken($user->id), 60 * 24 * 30);

        return redirect()->route('customer.view.profile')->with('success', 'Đăng nhập thành công');
    }

    public function logout()
    {
        Cookie::queue(Cookie::forget('_token_'));

        return redirect()->route('customer.view.login')->with('success_logout', 'Đăng xuất thành công');
    }
}
