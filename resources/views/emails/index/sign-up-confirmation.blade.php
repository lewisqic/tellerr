@extends('layouts.email')

@section('title', 'Tellerr Sign Up Confirmation')

@section('heading', 'Thank You For Signing Up With Tellerr!')
@section('heading-color', '#4CAF50')

@section('content')

    <p>
        Your Tellerr account has been setup successfully, you're just about ready to start accepting online payments.  Here are the details of your selected subscription plan:
    </p>

    <p style="margin-top: 30px;">
        <strong>Selected Plan:</strong> {{ $plan }}
    </p>
    <p>
        <strong>Subscription Fee:</strong> {{ $price_month }}<small>/month</small> <em>or</em> {{ $price_year }}<small>/year</small>
    </p>
    <p style="margin-bottom: 30px;">
        <strong>Trial End Date:</strong> {{ $trial_end_date }}
    </p>

    <p>
        <span style="color: #4CAF50;">To ensure uninterrupted service, be sure to upgrade to a paid Tellerr account before your free trial ends!</span>
    </p>

@endsection