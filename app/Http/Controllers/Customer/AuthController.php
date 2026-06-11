<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\CustomerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Store a newly registered user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if (User::where('userEmail', $request->email)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Email đã tồn tại');
        }

        $user = User::create([
            'userName' => $request->name,
            'userPhone' => $request->phone,
            'userEmail' => $request->email,
            'userAddress' => $request->address ?? '',
            'userPass' => Hash::make($request->password),
            'secret_key' => random_int(1000000, 9999999),
            'status' => 1,
        ]);

        Cookie::queue(Cookie::forget('_token_'));
        Cookie::queue('_token_', CustomerContext::createToken($user->userID), 60 * 24 * 30);

        return redirect()->route('customer.view.profile')->with('success', 'Đăng ký thành công');
    }

    /**
     * Authenticate the user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('userEmail', $request->email)
            ->whereNotNull('userPass')
            ->first();

        if (!$user || !Hash::check($request->password, $user->userPass)) {
            return redirect()->back()->with('error_login', 'Email hoặc mật khẩu không đúng');
        }

        Cookie::queue(Cookie::forget('_token_'));
        Cookie::queue('_token_', CustomerContext::createToken($user->userID), 60 * 24 * 30);

        return redirect()->route('customer.view.profile')->with('success', 'Đăng nhập thành công');
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        Cookie::queue(Cookie::forget('_token_'));

        return redirect()->route('customer.view.login')->with('success_logout', 'Đăng xuất thành công');
    }
}
