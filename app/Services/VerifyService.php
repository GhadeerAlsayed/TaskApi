<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VerifyService
{
    public function verify($code)
    {
        $user = Auth::user();
        $user = User::where('email', $user->email)->where('code',$code)->first();

        if ($user && $user->expire < now()) {
            return ['status' => false, 'message' => 'Invalid or expired verification code'];
        }
        $user->email_verified_at = true;
        $user->resetcode();

            return ['status' => true, 'message' => 'Email verified successfully!'];

    }
}
