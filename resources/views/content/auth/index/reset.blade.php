@extends('layouts.auth')

@section('content')

<h2 class="text-white mb-4">Reset Password</h2>

<p class="text-white mb-4">
    Enter your new password below.
</p>

<form action="{{ url('auth/reset') }}" method="post" class="validate" id="auth_form">
    <input type="hidden" name="id" value="{{ $id }}">
    <input type="hidden" name="code" value="{{ $code }}">
    {!! Html::hiddenInput(['ajax' => true]) !!}

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Email:</label>
        <div class="col-sm-10">
            <div class="form-control-static">{{ $email }}</div>
        </div>
    </div>

    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="New Password" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" autocomplete="off">
    </div>

    <div class="form-group">
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" data-fv-identical="true" data-fv-identical-field="password" autocomplete="off">
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-check">
                <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                    <input type="checkbox" name="login" id="login" value="1" checked><label for="login">Sign Me In</label>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <button type="submit" class="btn btn-light" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Submitting..." data-success-text="<i class='fa fa-check'></i> Success!"><i class="fa fa-lock"></i> Submit</button>
        </div>
    </div>

</form>

@endsection
