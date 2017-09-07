@extends('layouts.account')

@section('content')

{!! Breadcrumbs::render('account/settings') !!}

<h1>Account Settings</h1>

<div class="page-content container-fluid">

    <form action="{{ url('account/settings') }}" method="post" class="validate tabs labels-right" id="update_profile_form">
        {!! Html::hiddenInput(['method' => 'put']) !!}

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#general" role="tab">General</a>
            </li>
        </ul>

        <div class="tab-content mt-4">
            <div class="tab-pane active" id="general" role="tabpanel">

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Account URL</label>
                    <div class="col-sm-9 form-control-static">
                        https://<strong>{{ $company->subdomain }}</strong>.tellerr.com <a href="https://{{ $company->subdomain }}.tellerr.com" target="_blank"><i class="fa fa-external-link"></i></a>
                        <small class="form-text text-muted">This is your own personalized Teller<sup>&reg;</sup> domain name.  We generally do not allow you to change this value once it's been set.<br>If you have a compelling reason to change this value then please <a href="{{ url('contact-us') }}" target="_blank">contact us</a> with your request.</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Account Email</label>
                    <div class="col-sm-9">
                        <input type="text" name="email" class="form-control" value="{{ $company->email }}" data-fv-notempty="true" data-fv-emailaddress="true">
                        <small class="form-text text-muted">This is the default email address that all account related emails and notifications will be sent to.</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Company Name</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" value="{{ $company->name }}">
                        <small class="form-text text-muted">You can optionally enter in the name of your company or organization.</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Currency</label>
                    <div class="col-sm-9">
                        <select name="currency" class="form-control">
                            <option value="USD" {{ $company->currency == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ $company->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="GBP" {{ $company->currency == 'GBP' ? 'selected' : '' }}>GBP</option>
                            <option value="JPY" {{ $company->currency == 'JPY' ? 'selected' : '' }}>JPY</option>
                            <option value="CAD" {{ $company->currency == 'CAD' ? 'selected' : '' }}>CAD</option>
                            <option value="AUD" {{ $company->currency == 'AUD' ? 'selected' : '' }}>AUD</option>
                        </select>
                        <small class="form-text text-muted">This currency setting only applies to payments made by your own customers.  Your subscription payments to Tellerr will always be made in USD.</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Default Language</label>
                    <div class="col-sm-9">
                        <select name="language" class="form-control">
                            <option value="English" {{ $company->language == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Espanol" {{ $company->language == 'Espanol' ? 'selected' : '' }}>Español</option>
                            <option value="Francais" {{ $company->language == 'Francais' ? 'selected' : '' }}>Français</option>
                            <option value="Deutsche" {{ $company->language == 'Deutsche' ? 'selected' : '' }}>Deutsche</option>
                        </select>
                        <small class="form-text text-muted">Your default language setting will apply to your Tellerr account area as well as your own payment forms and invoices.</small>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group row mt-4">
            <div class="col-sm-9 ml-auto">
                <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>


    </form>

</div>


@endsection