<?php

namespace App\Services;

use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ForgetService
{
    public function forgotPassword($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->generate();

        Mail::to($user->email)->send(new VerificationEmail($user, $user->code));

        return response()->json(['message' => 'Reset code sent.'], 200);
    }
}
