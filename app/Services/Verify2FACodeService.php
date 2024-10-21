<?php

namespace App\Services;

use App\Models\User;

class Verify2FACodeService
{
    public function verify2FACode($email, $code)
    {
        $user = User::where('email', $email)->first();

        if (!$user || $user->code !== $code) {
            return response()->json(['message' => 'Invalid 2FA code.'], 403);
        }

        $token = $user->createToken('MyApiToken')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
