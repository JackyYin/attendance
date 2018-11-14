<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $customClaims = [
            'agent_id' => $request->user ? $request->user->id : 0,
            // api or web or admin
            'interface' => 'api',
        ];

        try {
            if (! $token = $this->jwt->claims($customClaims)->attempt($credentials)) {
                return response()->json([
                    'auth' => [
                        'invalid_credentials'
                    ]
                ], 401);
            }
        } catch (\Exception $e) {
            dump($e);
            return response()->json([
                'auth' => [
                    'could_not_create_token'
                ]
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => $this->jwt->factory()->getTTL() * 60
        ]);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
        ]);

        try {
            if (!$this->checkAgent($request->token, $request->user)) {
                return response()->json([
                    'auth' => [
                        'Unauthorized Agent.'
                    ]
                ], 401);
            }
        }  catch (TokenExpiredException $e) {
            return response()->json([
                'auth' => [
                    'User Token Expired.'
                ]
            ], 401);
        }

        $this->jwt->invalidate();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
        ]);

        try {
            if (!$this->checkAgent($request->token, $request->user)) {
                return response()->json([
                    'auth' => [
                        'Unauthorized Agent.'
                    ]
                ], 401);
            }
        }  catch (TokenBlacklistedException $e) {
            return response()->json([
                'auth' => [
                    'User Token Invalidated.'
                ]
            ], 401);
        }

        $token = $this->jwt->refresh();

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => $this->jwt->factory()->getTTL() * 60
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user
        ]);
    }
}
