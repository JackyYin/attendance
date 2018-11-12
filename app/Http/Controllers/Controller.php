<?php

namespace App\Http\Controllers;

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
        $model = $this->payload()->get('model');

        $id = $this->payload()->get('sub');

        if (!$model || !$id) {
            return false;
        }

        return $model::find($id);
    }

    public function payload()
    {
        return $this->jwt->getPayload();
    }
}
