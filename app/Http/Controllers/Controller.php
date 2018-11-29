<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Tymon\JWTAuth\JWTAuth;

class Controller extends BaseController
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

    public function user()
    {
        $model = $this->jwt->getPayload()->get('model');
        $id = $this->jwt->getPayload()->get('sub');

        return $model::find($id);
    }

    public function checkAgent($userToken, Agent $agent) : bool
    {
        $this->jwt->setToken($userToken);

        if ($this->jwt->getPayload()->get('agent_id') != $agent->id) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return call_user_func(static::$responseBuilder, $request, $errors);
        }

        return new JsonResponse($errors, JsonResponse::HTTP_BAD_REQUEST);
    }

}
