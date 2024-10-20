<?php

namespace App\Services;

use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService
{
    public function register(array $data, $profilePhotoPath)
    {
        $user = User::create([
            'email' => $data['email'],
            'phone' => $data['phone'],
            'full_name' => $data['full_name'],
            'password' => Hash::make($data['password']),
            'profile_photo' => $profilePhotoPath,
            'name' => $data['name'],
        ]);

        $user = User::where('email', $user->email)->first();
        $user->generate();

//        Mail::to($user->email)->send(new VerificationEmail($user,$user->code));

        return [
            'token' => $user->createToken('MyApiToken')->plainTextToken,
            'name' => $user->name,
            'email' => $user->email,
            'code' => $user->code
        ];
    }
}
