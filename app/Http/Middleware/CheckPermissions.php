<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckPermissions
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

        $action_full = $request->route()->getAction();
        list($controller, $action) = explode('@', class_basename($action_full['controller']));
        
        $all_routes = [];
        foreach ( \Config::get('permissions')[$area] as $group ) {
            foreach ( $group['actions'] as $key => $label ) {
                $actions = explode('|', $key);
                foreach ( $actions as $each ) {
                    $all_routes[] = $group['controller'] . '@' . $each;
                }
            }
        }

        $auth_user = \Auth::check();
        if ( $auth_user && (!$auth_user->roles->isEmpty() || !empty($auth_user->permissions)) && in_array(class_basename($action_full['controller']), $all_routes) ) {
            $permission = $controller . '@*' . $action . '*';
            // allow profile editing access
            if (
                ($request->isMethod('GET') && preg_match('/\/(\d+)\/edit/', url()->current(), $matches)) ||
                ($request->isMethod('PUT') && preg_match('/\/(\d+)$/', url()->current(), $matches)) )
            {
                if ( $matches[1] == $auth_user->id && \Request::has('_profile') && decrypt(\Request::input('_profile')) == $auth_user->id ) {
                    return $next($request);
                }
            }
            if ( !\Auth::hasAccess($permission) ) {
                $message = 'You do not have the necessary permissions to perform that action.';
                if ( $request->wantsJson() ) {
                    $data = ['success' => false, 'message' => $message];
                    return response()->json($data, 401);
                } else {
                    if ( !$request->isMethod('GET') ) {
                        \Msg::danger($message);
                        return back()->withInput();
                    } else {
                        $data = [
                            'layout' => $request->ajax() ? 'layouts.ajax' : 'layouts.' . $area
                        ];
                        return response()->view('errors.access-denied', $data);
                    }
                }
            }
        }

        return $next($request);
    }
}
