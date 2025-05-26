<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserInviteRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Services\Authservice;
use Arr;
use Illuminate\Http\Request;
use Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(
        protected Authservice $authservice
    ) {
    }

    public function register(CreateUserRequest $request)
    {
        $validated = $request->validated();
        $user = $this->authservice->registerUser($validated);
        return response()->success($user, "Account creation success", 201);
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only("email", "password");
            $loginData = $this->authservice->loginUser($credentials);

            return response()->success($loginData, "Login success.");

        } catch (JWTException $e) {
            return response()->error("Could not create token.", 500);
        }
    }

    public function invite(CreateUserInviteRequest $request)
    {
        try {
            $validated = array_merge($request->validated(), ["token" => Str::random(60)]);

            $invite = $this->authservice->createInvite($validated);
            return response()->success($invite->toArray(), "An invite has been sent to $invite->email successfully for a $invite->role account set-up.");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function refreshToken(){
        $newToken = auth()->refresh(true, true);
        return response()->success(["token"=>$newToken], "token refreshed successfully.");
    }
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->success([], "Logged out successfully.");
        } catch (JWTException $e) {
            return response()->error('Failed to logout, please try again', 500);
        }
    }
}
