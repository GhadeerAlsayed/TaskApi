<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LogRequest;
use App\Http\Requests\RegRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\verifyRequest;
use App\Mail\VerificationEmail;
use App\Models\User;
use App\Services\ForgetService;
use App\Services\LogService;
use App\Services\Resend2EmailService;
use App\Services\UserService;
use App\Services\VerifyService;
use App\Traits\ApiResponse;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    use ApiResponse;
    use Upload;

    public function __construct(private UserService $userService,private LogService $logService,private VerifyService $verifyService,private ForgetService $forgetService,private Resend2EmailService $resend2EmailService){}

//       ---------------------------------------------------Registration

    public function register(RegRequest $request)
    {

        $profilePhotoPath = $this->uploadFile($request, 'profile_photo', 'imgs', 'public');
        $data = $this->userService->register($request->all(), $profilePhotoPath, $request->ip());

        return $this->sendResponse(200, 'Register Successfully', $data);


    }

    public function verifyEmail(verifyRequest $request)
    {

        $data =$this->verifyService->verify($request->code);

        if ($data['status']) {
            return $this->sendResponse(['message' => $data['message']], 200);
        } else {
            return $this->sendResponse(['message' => $data['message']], 400);
        }
    }

    public function login(LogRequest $request)
    {
        $result = $this->logService->login($request->email_or_phone, $request->password);

        if (!$result['status']) {
            return response()->json(['message' => $result['message']], 401);
        }

        return response()->json(['message' => $result['message'],'code' => $result['code']]);
    }

    public function verify2FACode(Request $request)
    {
        $request->validate(['code' => 'required']);

        $user = Auth::user();

        $user = User::where('email', $user->email)->first();

        if (!$user && $user->code !== $request->code) {
            return response()->json(['message' => 'Invalid 2FA code.'], 403);
        }

        $token = $user->createToken('MyApiToken')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);
    }

//    -----------------------------------------------------RefreshToken

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $newToken = $user->createToken('MyApiToken')->plainTextToken;

        return response()->json(['message' => 'Token refreshed successfully', 'token' => $newToken], 201);


    }

//    -----------------------------------------------------Re-send verification code (two attempts every 10 minutes)

    public function resend2attempt()
    {
        return $this->resend2EmailService->resendVerificationCode();

    }

    public function resendVerificationCode(Request $request)
    {
        $user = $request->user();

        $user->generate();

        Mail::to($user->email)->send(new VerificationEmail($user ,$user->code));;

        return response()->json(['message' => 'تم إرسال رمز التحقق!'], 200);
    }

//    -----------------------------------------------------Forgot password.

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        $user->generate();


        Mail::to($user->email)->send(new VerificationEmail($user ,$user->code));

        return response()->json(['message' => 'Reset code sent.'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid code.'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->code = null;
        $user->save();

        return response()->json(['message' => 'Password has been reset.'], 200);
    }


}
