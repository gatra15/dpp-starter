<?php

namespace App\Services;

use App\Actions\Users\CreateUserAction;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct() {}

    public function login(array $credentials)
    {
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function register($request)
    {
        try {
            $user = app(CreateUserAction::class)->execute($request);
            $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
            return response()->json([
                'message' => 'User successfully registered',
                'user'    => $user,
                'token'   => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
