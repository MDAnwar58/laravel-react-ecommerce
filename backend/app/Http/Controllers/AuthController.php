<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckEmailForgetRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ForgetPassword;
use App\Mail\ResendEmailConfirmation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    function register(RegisterRequest $request)
    {
        $user = new User();
        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);
        $token = $user->createToken('ecom_app')->plainTextToken;

        return response()->json([
            'success' => 'User created successfully!',
            'token' => $token,
            'user' => $user
        ]);
    }
    function login(LoginRequest $request)
    {
        $userLogin = Auth::attempt($request->only('email', 'password'));
        if ($userLogin) {
            $user = Auth::user();
            $token = $user->createToken('ecom_app')->plainTextToken;

            return response()->json([
                'success' => 'Your account login successfully!',
                'token' => $token,
                'user' => $user
            ]);
        } else {
            $checkUserEmail = User::where('email', $request->email)->first();
            $checkPassword = Hash::check($request->password, $checkUserEmail->password);
            if (!$checkPassword) {
                return response()->json([
                    'error' => 'The selected password is invalid.'
                ]);
            }
        }
    }
    // function confirmationEmail(Request $request)
    // {
    //     $checkUser = User::where('email', $request->email)->first();
    //     $token = $checkUser->createToken('ecom_app')->plainTextToken;
    //     // $email = $checkUser->email;
    //     Mail::to($checkUser->email)->send(new ResendEmailConfirmation($checkUser));

    //     return response()->json([
    //         'success' => 'Resend your confirmation mail successfully!',
    //         'token' => $token,
    //         'user' => $checkUser
    //     ]);
    // }
    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => 'You logout successfully!'
        ]);
    }
    function forgetPassword(CheckEmailForgetRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        Mail::to($request->email)->send(new ForgetPassword($user));

        return response()->json([
            "success" => "Mail is send successfully! Please check your email and click 'password reset button'"
        ]);
    }
    function resetPassword(ResetPasswordRequest $request)
    {
        $checkEmail = User::where('email', $request->email)->first();
        $checkPassword = Hash::check($request->currentPassword, $checkEmail->password);
        if ($checkPassword) {
            $checkEmail->password = Hash::make($request->password);
            $checkEmail->save();

            return response()->json([
                'success' => 'Your account password has been change successfully!'
            ]);
        } else {
            return response()->json([
                'error' => 'Your current password or new password is not mass. Please enter your valid password!'
            ]);
        }
    }
}
