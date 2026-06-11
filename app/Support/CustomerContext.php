<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;
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

        $user = User::find((int) $parts[0]);
        if (!$user || !$user->secret_key || !$user->userPass) {
            return $guest;
        }

        if (!Hash::check($user->userID . '$' . $user->secret_key, $parts[1])) {
            return $guest;
        }

        return [
            'id' => $user->userID,
            'email' => $user->userEmail,
            'name' => $user->userName,
            'phone' => $user->userPhone,
            'address' => $user->userAddress,
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

    public static function createToken(int $userId): string
    {
        $user = User::findOrFail($userId);

        return $userId . '$' . Hash::make($userId . '$' . $user->secret_key);
    }
}
