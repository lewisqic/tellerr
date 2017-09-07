@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/verify') !!}

    <h1>Verify Stripe Account</h1>

    <div class="page-content container-fluid">

        <div class="card mb-4">
            <div class="card-header">
                <div class="float-left mr-4"><i class="fa fa-cc-stripe fa-4x" style="color: #32325D;"></i></div>
                <div class="font-18 mt-2">We've partnered with <strong>Stripe</strong>, one of the world's leading providers of online payments, to securely process all credit/debit card transactions made through Tellerr.</div>
            </div>
        </div>

        <div class="alert alert-alt alert-success mt-1 pr-0">

            <h5 class="mt-2 mb-3 font-18">Your Stripe account has been created and you can now accept live payments. All that's left is for you to verify the acccount.</h5>

            <div class="text-dark font-15">
                <p class="mb-2">
                    <i class="fa fa-angle-right fa-lg mr-2"></i> You should have received an email from Stripe asking you to activate your Stripe account by completing a simple one page form.
                </p>
                <p class="mb-2">
                    <i class="fa fa-angle-right fa-lg mr-2"></i> If you never received that email, or if you can't find it, you can click on the following link to manually verify your Stripe account: <a href="https://dashboard.stripe.com/account/activate?client_id={{ env('STRIPE_CLIENT_ID') }}&user_id={{ $company->stripe_account_id }}" target="_blank">Verify Stripe Account <i class="fa fa-external-link ml-1"></i></a>
                </p>
                <p class="mb-2">
                    <i class="fa fa-angle-right fa-lg mr-2"></i> Until you complete that verification process, your Stripe account will remain as a deferred status.</small>
                </p>
                <p class="mb-0">
                    <i class="fa fa-angle-right fa-lg mr-2"></i> Regarding the capabilities of a deferred Stripe account... once the first charge is made on your deferred Stripe account, you'll have one week to complete the verification process before Stripe blocks further charges. Stripe also block charges after a volume threshold is reached, usually around a few thousand dollars. After that threshold, the account must be verified within three weeks before Stripe refunds all charges.
                </p>
            </div>

        </div>



    </div>



@endsection