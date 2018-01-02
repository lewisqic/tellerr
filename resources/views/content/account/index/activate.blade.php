@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/activate') !!}

    <h1>
        Account Activation
        <small class="text-warning font-16">Activate your account to accept live payments. Until then, feel free to test your payment forms and invoices using <a href="{{ url('testing') }}" target="_blank">test credit card numbers</a>.</small>
    </h1>

    <div class="page-content container-fluid">


        <form action="{{ url('account/activate') }}" method="post" class="validate labels-right" id="activate_form">
            {!! Html::hiddenInput(['method' => 'post']) !!}

            <div class="card mb-4">
                <div class="card-header">
                    <div class="float-left mr-4"><i class="fa fa-cc-stripe fa-4x" style="color: #32325D;"></i></div>
                    <div class="font-18 mt-2">We've partnered with <strong>Stripe</strong>, one of the world's leading providers of online payments, to securely process all credit/debit card transactions made through Tellerr.  You'll be ready to accept live payments as soon as you get set up with a Stripe account.</div>
                </div>
            </div>


            <div class="alert alert-alt alert-warning mt-1 mb-4 pr-0">

                <h5 class="mt-2 mb-3 font-18">There are a few important things you need to know...</h5>

                <div class="text-dark font-15">
                    <p class="mb-2">
                        <i class="fa fa-angle-right fa-lg mr-2"></i> We're going to create your deferred Stripe account now.  The only information we need from you is the country you're in as well as the email address you want associated with your Stripe account.
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-angle-right fa-lg mr-2"></i> Once your deferred Stripe account is created, you will receive an email from Stripe asking you to complete the verification process by completing a simple one page form. <small>(this will only takes a few minutes)</small>
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-angle-right fa-lg mr-2"></i> Regarding the capabilities of a deferred Stripe account... once the first charge is made on your deferred Stripe account, you'll have one week to complete the verification process before Stripe blocks further charges. Stripe also block charges after a volume threshold is reached, usually around a few thousand dollars. After that threshold, the account must be verified within three weeks before Stripe refunds all charges.
                    </p>
                    <p class="mb-0">
                        <i class="fa fa-angle-right fa-lg mr-2"></i> All limitations of a deferred account will be removed after you've completed the Stripe account verification process.
                    </p>
                </div>


            </div>

            <div class="activation-error mb-4 {{ \Session::has('activation_failed') ? '' : 'display-none' }}">


                <div class="alert alert-alt alert-danger mt-1 pr-0">

                    <h5 class="mt-2 font-18">Uh oh, we ran into a problem when trying to create your new Stripe account.</h5>
                    <p>Error message: <em>It appears that a Stripe account with that email address already exists.</em></p>

                    <div class="text-dark font-15">
                        <p class="mb-2">
                            <i class="fa fa-angle-right fa-lg mr-2"></i> Please double check that the email address is correct, or enter in a new email address.
                        </p>
                        <p class="mb-0">
                            <i class="fa fa-angle-right fa-lg mr-2"></i> If you'd like to use an existing Stripe account, you can simply connect your current account to Tellerr rather than creating a new account.  To connect your account, please follow this link: <a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id={{ env('STRIPE_CLIENT_ID') }}&scope=read_write">Connect my existing account <i class="fa fa-external-link"></i></a>
                        </p>
                    </div>

                </div>

            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Country</label>
                <div class="col-sm-9">
                    <select name="country" class="form-control">
                        <option value="AU">Australia</option>
                        <option value="AT">Austria</option>
                        <option value="BE">Belgium</option>
                        <option value="CA">Canada</option>
                        <option value="DK">Denmark</option>
                        <option value="FI">Finland</option>
                        <option value="FR">France</option>
                        <option value="DE">Germany</option>
                        <option value="HK">Hong Kong</option>
                        <option value="IE">Ireland</option>
                        <option value="IT">Italy</option>
                        <option value="JP">Japan</option>
                        <option value="LU">Luxembourg</option>
                        <option value="NL">Netherlands</option>
                        <option value="NZ">New Zealand</option>
                        <option value="NO">Norway</option>
                        <option value="PT">Portugal</option>
                        <option value="SG">Singapore</option>
                        <option value="ES">Spain</option>
                        <option value="SE">Sweden</option>
                        <option value="CH">Switzerland</option>
                        <option value="GB">United Kingdom</option>
                        <option value="US" selected>United States</option>
                    </select>
                    <div class="form-text text-muted font-13">Please select the country that you want your Stripe account to be set up in.</div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Email</label>
                <div class="col-sm-9">
                    <input type="text" name="email" class="form-control" value="{{ $company->email }}" data-fv-notempty="true" data-fv-emailaddress="true">
                    <div class="form-text text-muted font-13">Enter the email address you want to be used in setting up your new Stripe account. (If you already have a Stripe account, enter in the email address for your existing account)</div>
                </div>
            </div>


            <div class="form-group row mt-4">
                <div class="col-sm-9 ml-auto">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Submit</button>
                </div>
            </div>

        </form>

    </div>



@endsection