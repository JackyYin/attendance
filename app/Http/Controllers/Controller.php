<?php

namespace App\Http\Controllers;

use App\Models\Agent;
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
}
