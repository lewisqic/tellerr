<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $area)
    {
        $auth_user = \Auth::check();
        if ( $auth_user === false ) {
            \Session::put('url.intended', preg_replace('/^\//', '', $_SERVER['REQUEST_URI']));
            $redirect = 'auth/login';
        } elseif ( $auth_user instanceof \Cartalyst\Sentinel\Users\EloquentUser ) {
            $route = User::$types[$auth_user->type]['route'];
            if ( $route != $area ) {
                $redirect = $route;
            }
            view()->share('auth_user', $auth_user);
            app()->singleton('auth_user', function($app) use($auth_user) {
                return $auth_user;
            });
            $user = User::find($auth_user->id);
            view()->share('app_user', $user);
            app()->singleton('app_user', function($app) use($user) {
                return $user;
            });
        }
        if ( isset($redirect) ) {
            if ( $request->ajax() || $request->wantsJson() ) {
                $data = ['success' => false, 'message' => 'Unauthorized', 'route' => $redirect];
                return response()->json($data, 401);
            } else {
                return redirect($redirect);
            }
        }
        return $next($request);
    }
}
