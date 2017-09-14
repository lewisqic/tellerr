<?php

/**
 * custom redirect helper function
 * @param  string $route 
 * @return redirect
 */
function redir($route) {
    if ( \Request::ajax() || \Request::wantsJson() ) {
        $data = [
            'success' => true,
            'route' => $route
        ];
        if ( \Session::has('notification.message') && \Session::has('notification.status') ) {
            $data['status'] = \Session::get('notification.status');
            $data['message'] = \Session::get('notification.message');
            \Session::forget('notification');
        }
        return response()->json($data);
    } else {
        if ( \Request::has('_redir') ) {
            $route = \Request::input('_redir');
        }
        return redirect($route);
    }
}

/**
 * custom function to check for permissions access
 * @param string $permission
 * @return boolean
 */
function has_access($permission) {
    $auth_user = \Auth::check();
    if ( $auth_user && (!$auth_user->roles->isEmpty() || !empty($auth_user->permissions)) ) {
        return \Auth::hasAccess($permission);
    }
    return true;
}

/**
 * output active class for nav item
 * @param string $route
 * @return string
 */
function nav_active($pattern) {
    $path = \Request::path();
    $pattern = str_replace('/', '\/', $pattern);
    return preg_match('/' . $pattern . '/', $path) ? 'active' : '';
}

/**
 * add the ordinal suffix to a number
 * @param int $number
 * @return string
 */
function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

/**
 * check if a subscription is in trial mode or not
 * @param  obj $subscription
 * @return boolean
 */
function is_trial($subscription)
{
    if ( !empty($subscription) && $subscription->status == 'trial' && $subscription->trial_ends_at ) {
        $trial_end_ts = \Carbon::createFromTimestamp($subscription->trial_ends_at->timestamp)->addDay()->startOfDay()->timestamp;
        return $trial_end_ts > time() ? true : false;
    } else {
        return false;
    }
}

/**
 * check if a subscription trial is expired or not
 * @param  obj $subscription
 * @return boolean
 */
function is_trial_expired($subscription)
{
    if ( !empty($subscription) && $subscription->status == 'trial' && $subscription->trial_ends_at ) {
        $trial_end_ts = \Carbon::createFromTimestamp($subscription->trial_ends_at->timestamp)->addDay()->startOfDay()->timestamp;
        return $trial_end_ts < time() ? true : false;
    } else {
        return false;
    }
}

/**
 * return the number of days left in a subscription trial
 * @param  obj $subscription
 * @return boolean
 */
function trial_days_left($subscription)
{
    if ( !empty($subscription) && $subscription->status == 'trial' && $subscription->trial_ends_at ) {
        $trial_end_ts = \Carbon::createFromTimestamp($subscription->trial_ends_at->timestamp)->addDay()->startOfDay();
        $days_left = $trial_end_ts->diffInHours() / 24;
        return ceil($days_left);
    } else {
        return 0;
    }
}