<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
//use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
    protected $jwt;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function authenticate(Request $request)
    {
        $credentials = [
            'email' => 'kadin56@gmail.com',
            'password' => 12345678
        ];

        $customClaims = [
            // 用token判斷
            'agents' => Agent::first() ? Agent::first()->name : 'App',
            // api or web or admin
            'interface' => 'api',
        ];

        try {
            //example: 修改token TTL (in minutes)
            $this->jwt->factory()->setTTL(1);

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
            'expires_in'   => $this->jwt->factory()->getTTL()*60
        ]);
    }

    public function logout(Request $request)
    {
        $this->jwt->invalidate();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh(Request $request)
    {
        $token = $this->jwt->refresh();

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => $this->jwt->factory()->getTTL()*60
        ]);
    }

    public function me()
    {
        return response()->json([
            'user' => $this->jwt->user()
        ]);

        dump($this->jwt->parseToken()->authenticate());
        dump($this->jwt->parseToken()->getPayload());
    }
}
