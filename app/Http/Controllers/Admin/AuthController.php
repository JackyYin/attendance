<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    public function __construct(Request $request)
    {
        $session = $request->session();

        view()->share('session', $session);
    }

    public function index(Request $request)
    {
        return $request->session()->all();
    }

    public function login()
    {
        return view('admin.pages.auth.login');
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
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:6'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dump($e->getResponse()->original);
            $request->session()->flash('danger', $e->getResponse()->original);
            return redirect()->route('admin.login');
        }

        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            $request->session()->flash('danger', [
                'password' => [
                    'incorrect password'
                ]
            ]);
            return redirect()->route('admin.login');
        }

        $request->session()->put('login_admin_'.md5('Illuminate\Auth\Guard'), $user->id);
        return redirect($this->redirectTo);
    }
}
