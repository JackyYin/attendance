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
            'agent' => $this->user()->name,
            // api or web or admin
            'interface' => 'api',
        ];

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = $this->jwt->claims($customClaims)->attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (\Exception $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
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

        $user = $this->user();

        try {
            if ($this->jwt->setToken($request->token)->getPayload()->get('agent') != $user->name) {
                return response()->json([
                    'auth' => [
                        'Unauthorized Agent.'
                    ]
                ], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'auth' => [
                    'User Token Expired.'
                ]
            ], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'auth' => [
                    'User Token Invalidated.'
                ]
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'auth' => [
                   $e->getMessage()
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

        $user = $this->user();

        try {
            if ($this->jwt->setToken($request->token)->getPayload()->get('agent') != $user->name) {
                return response()->json([
                    'auth' => [
                        'Unauthorized Agent.'
                    ]
                ], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'auth' => [
                    'User Token Expired.'
                ]
            ], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'auth' => [
                    'User Token Invalidated.'
                ]
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'auth' => [
                   $e->getMessage()
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

    public function me()
    {
        return response()->json([
            'user' => $this->jwt->user()
        ]);
    }
}
