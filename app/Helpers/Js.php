<?php

namespace App\Helpers;

class Js {


    /**
     * return javascript tag with our app config data
     * @return html
     */
    public static function config()
    {
        return '<script>const config = {_token: "' . csrf_token() . '", url: "' . url('') . '"};</script>';
    }


    /**
     * return javascript info for stripe
     * @return html
     */
    public static function stripeConfig()
    {
        return '<script>const stripe_config = {publishable_key: "' . env('STRIPE_PUBLISHABLE_KEY') . '"};</script>';
    }


    /**
     * return javascript tag with our notification flash data
     * @return html
     */
    public static function msg($show = true)
    {
        if ( $show && \Session::has('notification.status') && \Session::has('notification.message') ) {
            return '<script>const notification = {status: "' . \Session::get('notification.status') . '", message: "' . \Session::get('notification.message') . '"};</script>';
        } else {
            return '<script>const notification = null;</script>';
        }
    }




}