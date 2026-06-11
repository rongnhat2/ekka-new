<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerContext
{
    public static function user(Request $request): array
    {
        $guest = [
            'id' => null,
            'email' => null,
            'name' => null,
            'phone' => null,
            'address' => null,
            'is_login' => false,
        ];

        $token = $request->cookie('_token_');
        if (!$token) {
            return $guest;
        }

        $parts = explode('$', $token, 2);
        if (count($parts) !== 2) {
            return $guest;
        }

        $user = DB::table('customer')->where('id', (int) $parts[0])->first();
        if (!$user || !$user->secret_key || !$user->password) {
            return $guest;
        }

        if (!Hash::check($user->id . '$' . $user->secret_key, $parts[1])) {
            return $guest;
        }

        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'phone' => $user->phone,
            'address' => $user->address,
            'is_login' => true,
        ];
    }

    public static function cart(Request $request): array
    {
        return $request->session()->get('cart', []);
    }

    public static function cartCount(Request $request): int
    {
        return array_sum(self::cart($request));
    }

    public static function createToken(int $customerId): string
    {
        $user = DB::table('customer')->where('id', $customerId)->first();
        return $customerId . '$' . Hash::make($customerId . '$' . $user->secret_key);
    }
}
