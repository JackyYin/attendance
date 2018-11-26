<?php

namespace App\Http\Controllers\Web\Company;

use App\Models\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function __construct(Request $request)
    {
        $session = $request->session();

        view()->share('session', $session);
    }

    public function login()
    {
        return view('web.pages.company.auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        try {
            $this->validate($request, [
                'tax_id_number' => 'required|exists:companies,tax_id_number',
                'password' => 'required|min:6'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dump($e->getResponse()->original);
            $request->session()->flash('danger', $e->getResponse()->original);
            return redirect()->route('web.company.login');
        }

        $user = Company::where('tax_id_number', $request->tax_id_number)->first();

        if (!Hash::check($request->password, $user->password)) {
            $request->session()->flash('danger', [
                'password' => [
                    'incorrect password'
                ]
            ]);
            return redirect()->route('web.company.login');
        }

        $request->session()->put('login_web_company_'.md5('Illuminate\Auth\Guard'), $user->id);
        return redirect($this->redirectTo);
    }
}
