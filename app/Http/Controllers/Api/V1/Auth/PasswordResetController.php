<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ResetPasswordRequest;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function sendPasswordResetLink(Request $request){
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status = Password::RESET_LINK_SENT
        ?response()->success([], "If your email exists in our system, a password reset link has been sent.")
        :response()->error("unable to send reset link",400 );
    }

    public function resetPassword(ResetPasswordRequest $request){
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    "password" => Hash::make($password)
                ])->save();
            }
        );
        return $status = Password::PASSWORD_RESET
        ?response()->success([], "Password reset successful.")
        :response()->error("unable to send reset link",400);
    }
}
