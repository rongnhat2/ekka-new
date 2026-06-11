<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use Carbon\Carbon;
use Session;
use Hash;
use DB;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $admin_id     = static::checkLoginValid($request);
        if ($admin_id) {
            $name_cookie = Cookie::queue('_token__', static::createTokenClient($admin_id), 2628000);
            return redirect()->back()->with('success', 'Đăng nhập thành công');
        } else {
            return redirect()->back()->with('error', 'Tên tài khoản hoặc mật khẩu không chính xác');
        }
    }
    public function register()
    {
        // Contact to update Function
    }
    public function logout()
    {
        Cookie::queue(Cookie::forget('_token__'));
        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công');
    }


    private function checkLoginValid($request)
    {
        $email = $request->email;
        $password = $request->password;
        $user = DB::table('admin')->where('email', '=', $request->email)->first();

        if ($user) {
            return Hash::check($request->password, $user->password) ? $user->id : false;
        } else {
            return false;
        }
    }

    private function createTokenClient($id)
    {
        return $id . '$' . Hash::make($id . '$' . DB::table('admin')->where('id', '=', $id)->first()->secret_key);
    }
}
