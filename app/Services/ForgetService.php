<?php

namespace App\Services;

use Illuminate\Support\Facades\Password;

class ForgetService
{
    public function forgotPassword($email)
    {
        $status = Password::sendResetLink(['email' => $email]);

        return $status === Password::RESET_LINK_SENT
            ? ['status' => true, 'message' => 'Reset link sent to your email.']
            : ['status' => false, 'message' => 'Unable to send reset link.'];
    }
}
