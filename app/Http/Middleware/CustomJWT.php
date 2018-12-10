<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\JWTAuth;

class CustomJWT
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    protected $model;

    /**
     * Create a new middleware instance.
     *
     * @param  \Tymon\JWTAuth\JWTAuth $jwt
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $upperRole = ucfirst($role);
        $this->model = "App\\Models\\".$upperRole;

        try {
            $this->setRequest($request, $upperRole);
        } catch (\Exception $e) {
            return response()->json([
                'auth' => [
                    $e->getMessage()
                ]
            ], 401);
        }

        if (! $token = $this->jwt->getToken()) {
            return response()->json([
                'auth' => [
                    $upperRole.' Token Not Provided.'
                ]
            ], 401);
        }

        try {
            $this->jwt->authenticate($token);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'auth' => [
                    $upperRole.' Token Expired.'
                ]
            ], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'auth' => [
                    $upperRole.' Token Invalidated.'
                ]
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'auth' => [
                   $e->getMessage()
                ]
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'auth' => [
                   $e->getMessage()
                ]
            ], 401);
        }

        $payload = $this->jwt->getPayload();

        if ($payload->get('model') !== $this->model) {
            return response()->json([
                'auth' => [
                    $upperRole.' Token Model Type Error.'
                ]
            ], 401);
        }

        if (!$user = $this->model::find($payload->get('sub'))) {
            return response()->json([
                'auth' => [
                   $upperRole.' Not Found.'
                ]
            ], 401);
        }

        $request->merge([
            'user' => $user
        ]);

        return $next($request);
    }

    private function setRequest(Request $request, $upperRole)
    {
        $header = preg_replace('/\s+/', ' ', $request->header('Authorization'));

        $array = explode(" ", $header);

        if (array_key_exists(1, $array)) {
            $this->jwt->setToken($array[1]);
        } else {
            $message = $upperRole.' Token Not Provided.';

            throw new \Exception($message);
        }

        return true;
    }
}
