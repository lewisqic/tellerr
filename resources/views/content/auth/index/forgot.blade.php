@extends('layouts.auth')

@section('content')

<h2 class="text-white mb-4">Forgot Password</h2>

<p class="text-white mb-4">
    Enter your email address below to have us send you a password reset email.
</p>

<form action="{{ url('auth/forgot') }}" method="post" class="validate" id="auth_form">
    {!! Html::hiddenInput(['ajax' => true]) !!}

    <div class="form-group">
        <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="5" data-fv-stringlength-max="64" data-fv-emailaddress="true" autocomplete="off" autofocus>
    </div>

    <div class="row">
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-light" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Submitting..." data-success-text="<i class='fa fa-check'></i> Success!"><i class="fa fa-lock"></i> Submit</button>
        </div>
    </div>

</form>

@endsection