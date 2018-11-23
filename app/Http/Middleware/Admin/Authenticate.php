<?php

namespace App\Http\Middleware\Admin;

use App\Models\User;
use Closure;

class Authenticate
{
    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        if (! $userId = $request->session()->get('login_admin_'.md5('Illuminate\Auth\Guard'))) {
            $request->session()->flash('info', [
                'auth' => [
                    'Please login to continue.'
                ]
            ]);
            return redirect()->route('admin.login');
        }

        if (! User::find($userId)) {
            $request->session()->flash('info', [
                'auth' => [
                    'Please login again.'
                ]
            ]);
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
