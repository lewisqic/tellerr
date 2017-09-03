<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class Api
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = $request->header('X-Api-Token');
            if ( is_null($token) ) {
                throw new \Exception('Missing API Token');
            }
            $user_id = \Api::parseToken($token);
            $api_user = User::find($user_id);
            if ( is_null($api_user) ) {
                throw new \Exception('Invalid API Token');
            }
            app()->singleton('api_user', function($app) use($api_user) {
                return $api_user;
            });
        } catch ( \Exception $e ) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
        return $next($request);
    }
}
