<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService
{
    public function resetPassword(string $email, string $code, string $password)
    {
        $user = User::where('email', $email)
            ->where('code', $code)
            ->first();

        if (!$user) {
            return ['message' => 'Invalid code.', 'status' => 400];
        }

        $user->password = Hash::make($password);
        $user->code = null;
        $user->save();

        return ['message' => 'Password has been reset.', 'status' => 200];
    }
}
