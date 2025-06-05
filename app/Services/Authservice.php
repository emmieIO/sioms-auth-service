<?php

namespace App\Services;

use App\Models\Invite;
use App\Models\User;
use App\Notifications\InviteCreated;
use App\Notifications\VerifyEmailQueued;
use DB;
use Exception;
use Hash;
use Illuminate\Notifications\Notification;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authservice
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function registerUser(array $data)
    {
        $role = 'customer';
        $invite = null;

        if (!empty($data['invite_token'])) {
            $invite = Invite::where("token", $data["invite_token"])
                ->where("email", $data['email'])
                ->where("used", false)
                ->firstOrFail();

            if ($data['email'] !== $invite->email) {
                throw new Exception("Invalid email. Please use the email address to which this invite was sent.");
            }

            $role = $invite->role;
        }

        DB::transaction(function () use ($data, $role, $invite, &$user, &$token) {
            $user = new User([
                'fullname' => $data['fullname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->save();

            $user->assignRole($role);

            $token = JWTAuth::fromUser($user);
            if ($invite) {
                $invite->delete();
            }
        });
        $user->notify(new VerifyEmailQueued());
        return ['user' => $user, 'token' => $token];
    }

    public function loginUser(array $credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new Exception("Invalid credentials.", 401);
        }

        return [
            'token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }

    public function createInvite(array $data)
    {
        $invite = DB::transaction(function () use ($data) {
            return Invite::create($data);
        });

        $invite->notify(new InviteCreated($invite));
        return $invite;
    }



}
