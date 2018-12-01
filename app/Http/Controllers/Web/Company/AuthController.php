<?php

namespace App\Http\Controllers\Web\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Traits\Auth\JWTAuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use JWTAuthenticatesUsers;

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $this->validateLogin($request);

        $customClaims = [
            'interface' => 'web'
        ];

        if ($token = $this->attemptLogin($request, $customClaims)) {
            $this->jwt->setToken($token);
            dump($this->jwt->getPayload());
            return $this->sendLoginResponse($request, $token);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|exists:companies,tax_id_number',
            'password' => 'required|min:6'
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'tax_id_number';
    }

    /**
     * Get the login model to be used by the controller.
     *
     * @return string
     */
    public function model()
    {
        return 'App\\Models\\Company';
    }
}
