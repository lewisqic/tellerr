@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/billing/subscription') !!}

    <h1>Billing & Subscription</h1>

    <div class="page-content container-fluid">

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" role="tab">My Subscription</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('account/billing/payment-methods') }}" class="nav-link" role="tab">Payment Methods</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('account/billing/history') }}" class="nav-link" role="tab">Billing History</a>
            </li>
        </ul>

        <div class="tab-content mt-4">
            <div class="tab-pane active" role="tabpanel">

                <div class="labels-right">

                    @if ( $is_trial || $is_trial_expired )
                        <div class="alert alert-alt alert-{{ $is_trial_expired ? 'danger' : 'warning' }}">
                            @if ( $is_trial_expired )
                                <strong class="text-danger font-18"><i class="fa fa-exclamation-triangle"></i> Oh snap! Looks like your free trial has expired.</strong>
                                <p class="mt-1 mb-2">
                                    We'd love for you to stick around, upgrade now and get back to accepting online payments that work for you.
                                </p>
                            @else
                                <strong class="font-18"><i class="fa fa-info-circle"></i> Heads up! Your free trial ends
                                    @if ( $trial_days_left > 1 )
                                        in <span class="text-underline">{{ $trial_days_left }} days.</span>
                                    @else
                                        <span class="text-underline">today.</span>
                                    @endif
                                </strong>
                                <p class="mt-1 mb-2">
                                    You've had a chance to kick the tires, like what you see so far? Upgrade now to ensure uninterrupted service.
                                </p>
                            @endif
                            <div>
                                <a href="{{ url('account/billing/upgrade') }}" class="btn btn-success">Upgrade to a Paid Subscription <i class="fa fa-angle-double-right"></i></a>
                            </div>
                        </div>
                    @endif

                    @if ( $subscription->status == 'pending cancelation' )
                        <div class="alert alert-alt alert-warning">
                            <strong class="font-18"><i class="fa fa-info-circle text-info"></i> Your subscription is <em>pending cancelation</em>, you will not be charged again.</strong><br>
                            You still have full access until the end of your current billing cycle on <strong>{{ $subscription->next_billing_at->toFormattedDateString() }}</strong>, at which point your subscription will be canceled.<br>
                            Not quite ready to call it quits? You can
                            <form action="{{ url('account/billing/resume-subscription') }}" method="post" class="validate d-inline">
                                {!! Html::hiddenInput(['method' => 'post']) !!}
                                <a href="" class="submit-form confirm-click text-underline" data-title="Great! Here are the details..." data-text="By resuming your subscription, you will retain full account access.  Billing will resume and you will be charged {{ Format::currency($subscription->amount) }} on your next billing date." data-type="info" data-button-text="Resume Subscription" data-button-class="btn-success">resume your subscription</a>
                            </form>
                            at anytime.
                        </div>
                    @endif

                    @if ( $subscription->status == 'canceled' )
                        <div class="alert alert-alt alert-danger">
                            <strong class="font-18"><i class="fa fa-info-circle text-info"></i> Your subscription has been <em>canceled</em>.</strong>
                            <p class="mt-1 mb-2">
                                Cancelation notes: <em>{{ $subscription->status_notes }}</em>
                            </p>
                            <p class="mt-1 mb-2">
                                Want to give it another shot? You can <a href="{{ url('account/billing/upgrade') }}">resume your subscription</a> at anytime.
                            </p>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Selected Plan:</label>
                        <div class="col-sm-9 form-control-static">
                            <div class="row">
                                <div class="col-sm-3">
                                    {{ $subscription->plan_name }}
                                </div>
                                <div class="col-sm-9">
                                    @if ( $subscription->status == 'active' )

                                        @if ( $subscription->planChangeRequest )
                                            <div class="text-warning">
                                                You will be moved to the <strong>{{ $subscription->planChangeRequest->plan_name }}</strong> plan on <strong>{{ $subscription->next_billing_at->toFormattedDateString() }}</strong>.
                                                <form action="{{ url('account/billing/cancel-plan-change') }}" method="post" class="validate d-inline">
                                                    {!! Html::hiddenInput(['method' => 'post']) !!}
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary text-muted confirm-click" data-title="Cancel Plan Change" data-text="By canceling your plan change request, you will remain on your current subscription plan." data-button-text="Cancel Plan Change" data-button-class="btn-success">
                                                        <i class="fa fa-times text-danger"></i> Cancel Plan Change
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <a href="{{ url('account/billing/change-plan') }}" class="btn btn-sm btn-outline-secondary text-muted"><i class="fa fa-edit text-primary"></i> Change Plan</a>
                                        @endif

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ( $subscription->amount )
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Subscription Fee:</label>
                            <div class="col-sm-9 form-control-static">
                                <div class="row">
                                    <div class="col-sm-3">
                                        {{ Format::currency($subscription->amount) }}<small>/{{ $subscription->installment }}</small>
                                    </div>
                                    <div class="col-sm-9">
                                        @if ( $subscription->status == 'active' )
                                            <form action="{{ url('account/billing/change-installment') }}" method="post" class="validate d-inline">
                                                <input type="hidden" name="installment" value="{{ $opposite_installment }}">
                                                {!! Html::hiddenInput(['method' => 'post']) !!}
                                                <button type="submit" class="btn btn-sm btn-outline-secondary text-muted confirm-click" data-title="Change Billing Installment" data-text="By switching to {{ ucwords($opposite_installment) }}ly billing, you will be charged <strong>{{ Format::currency($subscription->{'plan_price_' . $opposite_installment}) }}</strong> each {{ $opposite_installment }}, starting on your next billing date." data-type="info" data-button-text="Change to {{ ucwords($opposite_installment) }}ly Billing" data-button-class="btn-success">
                                                    <i class="fa fa-calendar text-primary"></i> Change to {{ ucwords($opposite_installment) }}ly Billing
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Subscription Status:</label>
                        <div class="col-sm-9 form-control-static">
                            <div class="row">
                                <div class="col-sm-3">
                                    {{ ucwords($subscription->status) }}
                                    @if ( $is_trial_expired )
                                        Expired
                                    @endif
                                </div>
                                <div class="col-sm-9">
                                    @if ( $subscription->status == 'active' )
                                        <form action="{{ url('account/billing/cancel-subscription') }}" method="post" class="validate d-inline">
                                            {!! Html::hiddenInput(['method' => 'post']) !!}
                                            <button type="submit" class="btn btn-sm btn-outline-danger confirm-click" data-text="By canceling your subscription, you will retain access until your next billing date. You will not be charged again." data-button-text="Yes, cancel my subscription"><i class="fa fa-ban"></i> Cancel Subscription</button>
                                        </form>
                                    @endif
                                    @if ( $subscription->status == 'pending cancelation' )
                                        <form action="{{ url('account/billing/resume-subscription') }}" method="post" class="validate d-inline">
                                            {!! Html::hiddenInput(['method' => 'post']) !!}
                                            <button type="submit" class="btn btn-sm btn-outline-success confirm-click" data-title="Great! Here are the details..." data-text="By resuming your subscription, you will retain full account access.  Billing will resume and you will be charged {{ Format::currency($subscription->amount) }} on your next billing date." data-type="info" data-button-text="Resume Subscription" data-button-class="btn-success"><i class="fa fa-undo"></i> Resume Subscription</button>
                                        </form>
                                    @endif
                                    @if ( $subscription->status == 'canceled' )
                                        <a href="{{ url('account/billing/upgrade') }}" class="btn btn-sm btn-outline-success"><i class="fa fa-undo"></i> Resume Subscription</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ( $subscription->next_billing_at )
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">{{ $subscription->status == 'pending cancelation' ? 'Last Access Date' : 'Next Billing Date' }}:</label>
                            <div class="col-sm-9 form-control-static">
                                {{ $subscription->next_billing_at->toFormattedDateString() }}
                            </div>
                        </div>
                    @endif

                    @if ( $is_trial )
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Free Trial End Date:</label>
                            <div class="col-sm-9 form-control-static">
                                {{ $subscription->trial_ends_at->toFormattedDateString() }}
                            </div>
                        </div>
                    @endif

                    @if ( $subscription->canceled_at )
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Cancelation Date:</label>
                            <div class="col-sm-9 form-control-static">
                                {{ $subscription->canceled_at->toFormattedDateString() }}
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Sign Up Date:</label>
                        <div class="col-sm-9 form-control-static">
                            {{ $subscription->created_at->toFormattedDateString() }}
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection
