<?php

namespace App\Services;

use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Resend2EmailService
{

    public function resendVerificationCode()
    {
        $user = Auth::user();
        $user = User::where('email', $user->email)->first();

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email is already verified.'], 400);
        }

        $lastAttempt = Carbon::parse($user->expire);
        $attempts = $user->verification_code_attempts;

        if ($lastAttempt && $lastAttempt->diffInMinutes(now()) < 10 && $attempts >= 2) {
            return response()->json(['message' => 'Too many attempts. Please try again later.'], 429);
        }

        if ($lastAttempt && $lastAttempt->diffInMinutes(now()) >= 10) {
            $user->verification_code_attempts = 0;
        }

        $user->verification_code_attempts += 1;
        $user->expire = now();
        $user->code = Str::random(6);
        $user->save();

        return response()->json(['message' => 'Verification code resent successfully.']);
    }


}
