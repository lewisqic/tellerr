@extends('layouts.auth')

@section('content')

<h2 class="text-white mb-4">Sign In</h2>
<form action="{{ url('auth/login') }}" method="post" class="validate" id="auth_form">
    {!! Html::hiddenInput(['ajax' => true]) !!}

    <div class="form-group">
        <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="5" data-fv-stringlength-max="64" data-fv-emailaddress="true" autocomplete="off" autofocus>
    </div>

    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Password" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" autocomplete="off">
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-check">
                <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                    <input type="checkbox" name="remember" id="remember" value="1"><label for="remember">Remember Me</label>
                </div>
            </div>
            <div class="forgot">
                <a href="{{ url('auth/forgot') }}">Forgot Password?</a>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <button type="submit" class="btn btn-light" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Signing in..." data-success-text="<i class='fa fa-check'></i> Success!"><i class="fa fa-lock"></i> Submit</button>
        </div>
    </div>

</form>

@endsection