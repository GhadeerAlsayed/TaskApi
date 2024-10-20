<?php

namespace App\Services;

use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LogService
{
    public function login($emailOrPhone, $password)
    {
        $user = User::where('email', $emailOrPhone)->orWhere('phone', $emailOrPhone)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return ['status' => false, 'message' => 'Invalid credentials'];
        }

        $user->generate();
//        Mail::to($user->email)->send(new VerificationEmail($user,$user->code));

        return ['status' => true, 'message' => 'Email verified successfully','code' => $user->code];

    }

}
