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
        try {
            $model = $this->payload()->get('model');
            $id = $this->payload()->get('sub');
        } catch (\Exception $e) {
            dump($e);
            return NULL;
        }

        if (!$model || !$id) {
            return NULL;
        }

        return $model::find($id);
    }

    /**
     * Get the raw Payload instance.
     *
     * @return \Tymon\JWTAuth\Payload
     */
    public function payload()
    {
        return $this->jwt->getPayload();
    }

    public function checkAgent($userToken, Agent $agent) : bool
    {
        $this->jwt->setToken($userToken);

        if ($this->payload()->get('agent_id') != $agent->id) {
            return false;
        }

        return true;
    }
}
