<?php

namespace App\Http\Middleware;

use App\Models\Agent;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\JWTAuth;

class CustomJWTAgent
{
    const MODEL = Agent::class;
    /**
     * The authentication guard factory instance.
     *
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

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
    public function handle($request, Closure $next)
    {
        $authorization = preg_replace('/\s+/', ' ', $request->header('Authorization'));
        $authorization = explode(" ", $authorization);
        if (array_key_exists(1, $authorization)) {
            $this->jwt->setToken($authorization[1]);
        } else {
            return response()->json([
                'auth' => [
                    $upperRole.' Token Not Provided.'
                ]
            ], 401);
        }

        if (! $token = $this->jwt->getToken()) {
            return response()->json([
                'auth' => [
                    'Agent Token Not Provided.'
                ]
            ], 401);
        }

        try {
            $this->jwt->authenticate($token);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'auth' => [
                    'Agent Token Expired.'
                ]
            ], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'auth' => [
                    'Agent Token Invalidated.'
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

        if ($payload->get('model') !== self::MODEL) {
            return response()->json([
                'auth' => [
                    'Agent Token Model Type Error.'
                ]
            ], 401);
        }

        if (!$agent = Agent::find($payload->get('sub'))) {
            return response()->json([
                'auth' => [
                   'Agent Not Found.'
                ]
            ], 401);
        }

        $request->merge([
            'user' => $agent
        ]);

        return $next($request);
    }
}
