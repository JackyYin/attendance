<?php

namespace App\Http\Middleware\Web;

use App\Models\Company;
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
    public function handle($request, Closure $next, $role)
    {
        if (! $id = $request->session()->get('login_web_'.$role.'_'.md5('Illuminate\Auth\Guard'))) {
            $request->session()->flash('info', [
                'auth' => [
                    'Please login to continue.'
                ]
            ]);
            return redirect()->route('web.'.$role.'.login');
        }

        $model = "App\\Models\\".ucfirst($role);
        dump($model);

        if (! $user = $model::find($id)) {
            $request->session()->flash('info', [
                'auth' => [
                    'Please login again.'
                ]
            ]);
            return redirect()->route('web.'.$role.'.login');
        }

        $request->merge([
            $role => $user
        ]);

        return $next($request);
    }
}
