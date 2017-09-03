@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account') !!}

    <h1>Dashboard</h1>

    <div class="page-content container-fluid">
        <div class="row">

            Wizard content:

            company settings
            braintree verification info
            payout method, checking vs venmo
            upgrade account to receive payouts (escrow)
            refund/chargeback policy
            terms/merchant agreement
            where do I go from here directions

        </div>
    </div>



@endsection