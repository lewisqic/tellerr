@extends('layouts.account')

@section('content')

    <div class="page-content container-fluid">

        <h3 class="text-center col-sm-12">Welcome to <strong>Tellerr</strong>! Let's take care of a few setup items...</h3>

        <div class="wizard-progress">
            <div class="row">
                <div class="col-sm-4 step-heading active" data-step="1">
                    1. Account Settings
                </div>
                <div class="col-sm-4 step-heading" data-step="2">
                    2. Account Activation
                </div>
                <div class="col-sm-4 step-heading" data-step="3">
                    3. What's Next
                </div>
            </div>
        </div>


        <form action="{{ url('account/setup') }}" method="post" class="labels-right" id="setup_wizard_form">
            {!! Html::hiddenInput(['method' => 'post']) !!}


            <div class="step-content" data-step="1">

                <div class="row mb-4">
                    <div class="col-sm-9 ml-auto">
                        <h4>Account Settings</h4>
                        <div class="text-muted">
                            You can change these settings at any time under <em>System -> Account Settings</em>.
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Company Name</label>
                    <div class="col-sm-6">
                        <input type="text" name="name" class="form-control" value="{{ $company->name }}">
                        <div class="form-text text-muted font-13">You can <em>optionally</em> enter in the name of your company or organization.</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Account Email</label>
                    <div class="col-sm-6">
                        <input type="text" name="email" class="form-control" value="{{ $company->email }}" data-fv-notempty="true" data-fv-emailaddress="true">
                        <div class="form-text text-muted font-13">This is the default email address that all account related emails and notifications will be sent to.</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Currency</label>
                    <div class="col-sm-6">
                        <select name="currency" class="form-control">
                            <option value="USD" {{ $company->currency == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ $company->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="GBP" {{ $company->currency == 'GBP' ? 'selected' : '' }}>GBP</option>
                            <option value="JPY" {{ $company->currency == 'JPY' ? 'selected' : '' }}>JPY</option>
                            <option value="CAD" {{ $company->currency == 'CAD' ? 'selected' : '' }}>CAD</option>
                            <option value="AUD" {{ $company->currency == 'AUD' ? 'selected' : '' }}>AUD</option>
                        </select>
                        <div class="form-text text-muted font-13">This currency setting only applies to payments made by your own customers.  Your subscription payments to Tellerr will always be made in USD.</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Default Language</label>
                    <div class="col-sm-6">
                        <select name="language" class="form-control">
                            <option value="English" {{ $company->language == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Espanol" {{ $company->language == 'Espanol' ? 'selected' : '' }}>Español</option>
                            <option value="Francais" {{ $company->language == 'Francais' ? 'selected' : '' }}>Français</option>
                            <option value="Deutsche" {{ $company->language == 'Deutsche' ? 'selected' : '' }}>Deutsche</option>
                        </select>
                        <div class="form-text text-muted font-13">Your default language setting will apply to your Tellerr account area as well as your own payment forms and invoices.</div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-sm-12 text-center">
                        <a href="#" class="btn btn-primary btn-lg next-step save-settings">Next <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>

            </div>

            <div class="step-content display-none" data-step="2">

                <div class="row mb-4">
                    <div class="col-sm-9 ml-auto">
                        <h4>Account Activation</h4>
                        <div class="text-muted">
                            You must complete the account activation process before accepting live payments.
                        </div>
                    </div>
                </div>

                {{--<div class="row">
                    <div class="col-sm-6 mx-auto">

                        <div class="alert alert-alt alert-warning pr-2">
                            <strong><i class="fa fa-info-circle"></i> Your account is currently in sandbox mode.</strong><br>
                            <small>Your account is fully functional, you can even create and test payment forms.  However, while in sandbox mode, your payment forms will only accept test card numbers and no real funds will be transferred. </small>
                        </div>

                    </div>
                </div>--}}

                <div class="row mb-4">
                    <div class="col-sm-6 mx-auto font-18">

                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mr-4"><i class="fa fa-cc-stripe fa-4x" style="color: #32325D;"></i></div>
                                <div class="font-18 mt-2">We've partnered with <strong>Stripe</strong>, one of the world's leading providers of online payments, to securely process all credit/debit card transactions made through Tellerr.</div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Activation Option</label>
                    <div class="col-sm-6 form-control-static">
                        <div class="abc-radio abc-radio-primary">
                            <input type="radio" name="activation" id="activation_later" value="later" data-fv-notempty="true" {{ $stripe_account_created ? 'disabled' : '' }}>
                            <label for="activation_later">I'll do it later, just let me pass!</label>
                            <div class="form-text text-muted font-13 display-none">No problem, feel free to complete your account activation whenever you're ready.</div>
                        </div>
                        <div class="abc-radio abc-radio-primary mt-3">
                            <input type="radio" name="activation" id="activation_now" value="now" data-fv-notempty="true"  {{ $stripe_account_created ? 'checked' : '' }}>
                            <label for="activation_now">I want to start the activation process now.</label>
                        </div>
                    </div>
                </div>

                <div class="activation-fields display-none mt-3">

                    <div class="row mb-3">
                        <div class="col-sm-6 mx-auto">

                            <div class="alert alert-alt alert-primary mt-1 pr-0">

                                <h5 class="mt-2 mb-3 font-18">Great choice! Now, there are a few important things you need to know...</h5>

                                <div class="text-dark font-15">
                                    <p class="mb-2">
                                        <i class="fa fa-angle-right fa-lg mr-2"></i> We're going to create your deferred Stripe account right now.  The only information we need from you is the country you're in as well as the email address you want associated with your Stripe account.
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

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Country</label>
                        <div class="col-sm-6">
                            <select name="stripe_country" class="form-control">
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
                        <div class="col-sm-6">
                            <input type="text" name="stripe_email" class="form-control" value="{{ $company->email }}" data-fv-notempty="true" data-fv-emailaddress="true">
                            <div class="form-text text-muted font-13">Enter the email address you want to be used in setting up your new Stripe account. (If you already have a Stripe account, enter in the email address for your existing account)</div>
                        </div>
                    </div>

                </div>

                <div class="activation-success mt-3 {{ $stripe_account_created ? '' : 'display-none' }}">

                    <div class="row mb-3">
                        <div class="col-sm-6 mx-auto">

                            <div class="alert alert-alt alert-success mt-1 pr-0">
                                <h5 class="mt-2 mb-3 font-18">Success! Your Stripe account has been created.</h5>
                                <div class="text-dark font-15">
                                    <p class="mb-0">
                                        <i class="fa fa-angle-right fa-lg mr-2"></i> Keep an eye out for the email sent from Stripe that will allow you finalize the account verification process.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="activation-error display-none">

                    <div class="row mb-3">
                        <div class="col-sm-6 mx-auto">

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
                    </div>

                </div>

                <div class="row mt-5">
                    <div class="col-sm-12 text-center">
                        <a href="#" class="btn btn-outline-secondary btn-lg previous-step"><i class="fa fa-long-arrow-left"></i> Back</a>
                        <a href="#" class="btn btn-primary btn-lg next-step stripe">Next <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>

            </div>

            <div class="step-content display-none" data-step="3">

                <div class="row mb-4">
                    <div class="col-sm-9 ml-auto">
                        <h4>What's Next</h4>
                        <div class="text-muted">
                            You're just about done with the setup wizard, so what's next?
                        </div>
                    </div>
                </div>

                <div class="row mt-2 mb-3">
                    <div class="col-sm-6 mx-auto font-18">
                        To kick things off, we recommend...
                    </div>
                </div>

                <div class="row mt-2 mb-4">
                    <div class="col-sm-6 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mr-4"><i class="fa fa-wpforms fa-4x text-muted"></i></div>
                                <h5 class="font-19">Payment Forms</h5>
                                <div class="font-14">Create your own custom payment forms that can be used to collect one time payments as well as setup recurring subscription billing for your customers.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2 mb-3">
                    <div class="col-sm-6 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-left mr-4"><i class="fa fa-file-text-o fa-4x text-muted"></i></div>
                                <h5 class="font-19">Invoicing</h5>
                                <div class="font-14">Create and manage online invoices that can be quickly and easily sent to your customers to collect payment.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-sm-12 text-center">
                        <a href="#" class="btn btn-outline-secondary btn-lg previous-step"><i class="fa fa-long-arrow-left"></i> Back</a>
                        <a href="#" class="btn btn-primary btn-lg finish-setup"><i class="fa fa-check"></i> Finish Setup!</a>
                    </div>
                </div>

            </div>

        </form>

    </div>



@endsection

@push('scripts')
    <script src="{{ url('assets/js/modules/wizard.js') }}"></script>
@endpush