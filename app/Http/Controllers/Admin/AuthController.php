<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $adminId = $this->checkLoginValid($request);
        if ($adminId) {
            Cookie::queue('_token__', $this->createTokenClient($adminId), 2628000);

            return redirect()->back()->with('success', 'Đăng nhập thành công');
        }

        return redirect()->back()->with('error', 'Tên tài khoản hoặc mật khẩu không chính xác');
    }

    public function logout()
    {
        Cookie::queue(Cookie::forget('_token__'));

        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công');
    }

    private function checkLoginValid(Request $request)
    {
        $admin = Admin::where('adminEmail', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->adminPass)) {
            return false;
        }

        return $admin->adminID;
    }

    private function createTokenClient(int $id): string
    {
        $admin = Admin::findOrFail($id);

        return $id . '$' . Hash::make($id . '$' . $admin->secret_key);
    }
}
