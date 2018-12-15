<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Tymon\JWTAuth\JWTAuth;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *   title="Attendance API",
     *   description="API for both mobile and frontend pages",
     *   version="1",
     *   @OA\Contact(
     *     name="Jacky Yin",
     *     email="jjyyg1123@gmail.com",
     *   ),
     *   @OA\License(
     *     name="Apache 2.0",
     *     url="http://www.apache.org/licenses/LICENSE-2.0.html",
     *   )
     * )
     */
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
