@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

@if ( $title == 'Edit' )
    {!! Breadcrumbs::render('account/forms/edit', $form) !!}
@else
    {!! Breadcrumbs::render('account/forms/create') !!}
@endif

<h1>{{ $title }} Payment Form <small>{{ $form->name or '' }}</small></h1>

<div class="page-content container-fluid">

    <form action="{{ $action }}" method="post" class="validate steps labels-right" id="create_edit_form_form">
        <input type="hidden" name="id" value="{{ $form->id or '' }}">
        {!! Html::hiddenInput(['method' => $method]) !!}

        <div class="row">
            <div class="col-sm-3 form-steps">

                <ul>
                    <li class="active" data-step="1">General Details</li>
                    <li class="" data-step="2">Amount / Fees</li>
                    <li class="" data-step="3">Payment Frequency</li>
                    <li class="" data-step="4">Additional Fields</li>
                    <li class="" data-step="5">Success / Confirmation</li>
                    <li class="" data-step="6">Theme / Layout</li>
                </ul>

            </div>
            <div class="col-sm-9 form-fields">

                <div class="form-step-content" data-step="1">

                    <h4>General Details</h4>

                    <div class="form-group">
                        <label>Form Title<span class="required text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Form Title" value="{{ $form->title or old('title') }}" data-fv-notempty="true" autofocus>
                        <div class="form-text text-muted font-13">The form title will be shown at the top of the form page.</div>
                    </div>

                    <div class="form-group">
                        <label>Show Form Description</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".description-wrapper" name="show_description" id="description_yes" value="yes">
                            <label for="description_yes">Yes</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-hide=".description-wrapper" name="show_description" id="description_no" value="no" checked>
                            <label for="description_no">No</label>
                        </div>
                        <div class="description-wrapper child-content display-none ignore-validation">
                            <div class="form-group">
                                <label>Form Description<span class="required text-danger">*</span></label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Form Description" data-fv-notempty="true"></textarea>
                                <div class="form-text text-muted font-13">Description or instructions that will be shown at the top of your form, right under the form title.</div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Add Terms & Conditions Checkbox</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".terms-wrapper" name="show_terms" id="terms_yes" value="yes">
                            <label for="terms_yes">Yes</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-hide=".terms-wrapper" name="show_terms" id="terms_no" value="no" checked>
                            <label for="terms_no">No</label>
                        </div>
                        <div class="terms-wrapper child-content display-none">
                            <div class="form-group">
                                <label>Terms & Conditions Text</label>
                                <textarea class="form-control" name="terms" rows="5" placeholder="Terms & Conditions Text"></textarea>
                                <div class="form-text text-muted font-13">Enter in the terms & conditions that customers must agree to. (Your default T&C can be found under <em>System -> Account Settings</em>)</div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Limit Submissions / Disable Form</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".disable-wrapper" name="disable" id="disable_yes" value="yes">
                            <label for="disable_yes">Yes</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-hide=".disable-wrapper" name="disable" id="disable_no" value="no" checked>
                            <label for="disable_no">No</label>
                        </div>

                        <div class="disable-wrapper child-content display-none">

                            <div class="form-group">
                                <label>Maximum Allowed Submissions</label>
                                <input type="text" name="submission_limit" class="form-control" placeholder="0" value="">
                                <div class="form-text text-muted font-13">The form will be disabled after this number of successful submissions.</div>
                            </div>
                            <div class="form-group">
                                <label>Disable Form Date</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="date" name="disable_date" class="form-control" placeholder="mm/dd/yyyy" value="">
                                </div>
                                <div class="form-text text-muted font-13">The form will be disabled on this date.</div>
                            </div>

                        </div>

                    </div>

                    <div class="form-group">
                        <label>Enable Coupons</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" name="enable_coupons" id="coupons_yes" value="yes">
                            <label for="coupons_yes">Yes</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" name="enable_coupons" id="coupons_no" value="no" checked>
                            <label for="coupons_no">No</label>
                        </div>
                        <div class="form-text text-muted font-13">Whether or not you want to allow coupon to be used on this form.</div>

                    </div>

                    <div class="controls">
                        <a href="#" class="btn btn-primary next-step"><i class="fa fa-angle-down"></i> Amount / Fees</a>
                        <button type="submit" class="btn btn-outline-success save" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save & Finish</button>
                    </div>

                </div>

                <div class="form-step-content display-none" data-step="2">

                    <h4>Amount / Fees</h4>


                    <div class="form-group">
                        <label>Amount Type</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".amount-type-set-specific" data-hide=".amount-type-customer-decide, .suggested-title" name="amount_type" id="amount_type_set_specific" value="set_specific" checked>
                            <label for="amount_type_set_specific">Set Specific Amount(s)</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".amount-type-customer-decide, .suggested-title" data-hide=".amount-type-set-specific" name="amount_type" id="amount_type_customer_decide" value="customer_decide">
                            <label for="amount_type_customer_decide">Let Customer Decide</label>
                        </div>

                        <div class="amount-type-customer-decide child-content display-none ignore-validation">

                            <div class="form-group">
                                <label>Offer Suggested Amounts</label>
                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" class="toggle-content" data-show=".amount-type-set-specific" name="suggested" id="suggested_yes" value="yes">
                                    <label for="suggested_yes">Yes</label>
                                </div>
                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" class="toggle-content" data-hide=".amount-type-set-specific" name="suggested" id="suggested_no" value="no" checked>
                                    <label for="suggested_no">No</label>
                                </div>
                                <div class="form-text text-muted font-13">If enabled, customers will see suggested amounts but they can still enter in their own custom amount.</div>
                            </div>

                        </div>

                        <div class="amount-type-set-specific child-content" data-ignore-validation="true">

                            <div class="form-group">
                                <label><span class="suggested-title display-none">Suggested</span> Amount(s)</label>
                                <div class="row mb-3 amount-row">
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                            <input type="text" name="amount[]" class="form-control" placeholder="0.00" value="" data-fv-notempty="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount_description[]" class="form-control" placeholder="Amount Description" value="">
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="mt-2 display-none show-after-clone">
                                            <a href="#" class="text-danger delete-closest" data-closest=".amount-row"><i class="fa fa-trash-o fa-lg"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-secondary text-primary clone-content" data-content=".amount-row:first" data-insert-after=".amount-row:last"><i class="fa fa-plus"></i> Add Amount</a>

                            </div>

                        </div>

                    </div>

                    <div class="form-group">
                        <label>Additional Fee / Tax</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".fee-wrapper" name="charge_fee" id="fee_yes" value="yes">
                            <label for="fee_yes">Yes</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-hide=".fee-wrapper" name="charge_fee" id="fee_no" value="no" checked>
                            <label for="fee_no">No</label>
                        </div>
                        <div class="form-text text-muted font-13">This is a one time charge for <em>One Time</em> type payments. This is a recurring charge for <em>Recurring / Subscription</em> type payments.</div>

                        <div class="fee-wrapper child-content display-none ignore-validation">

                            <div class="form-group">
                                <label>Fee Name<span class="required text-danger">*</span></label>
                                <input type="text" name="fee_name" class="form-control" placeholder="Fee Name" value="{{ $form->fee_name or old('fee_name') }}" data-fv-notempty="true">
                                <div class="form-text text-muted font-13">Let customers know what this fee is, such as "Sales Tax" or "Shipping Fee".</div>
                            </div>

                            <div class="form-group">
                                <label>Fee Type</label>

                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" class="toggle-content" data-show=".fee-type-fixed-wrapper" data-hide=".fee-type-percentage-wrapper" name="fee_type" id="fee_type_fixed" value="fixed" checked>
                                    <label for="fee_type_fixed">Fixed Amount</label>
                                </div>
                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" class="toggle-content" data-show=".fee-type-percentage-wrapper" data-hide=".fee-type-fixed-wrapper" name="fee_type" id="fee_type_percentage" value="percentage">
                                    <label for="fee_type_percentage">Percentage</label>
                                </div>
                                <div class="fee-type-fixed-wrapper child-content" data-ignore-validation="true">
                                    <div class="form-group">
                                        <label>Amount<span class="required text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                            <input type="text" name="fee_amount" class="form-control" placeholder="0.00" value="" data-fv-notempty="true" data-fv-numeric="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="fee-type-percentage-wrapper child-content display-none ignore-validation">
                                    <div class="form-group">
                                        <label>Percentage<span class="required text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="fee_percentage" class="form-control" placeholder="0" value="" data-fv-notempty="true" data-fv-numeric="true">
                                            <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="form-group">
                        <label>One Time, Upfront Charge</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".upfront-wrapper" name="upfront" id="upfront_yes" value="yes">
                            <label for="upfront_yes">Yes</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-hide=".upfront-wrapper" name="upfront" id="upfront_no" value="no" checked>
                            <label for="upfront_no">No</label>
                        </div>
                        <div class="form-text text-muted font-13"><span class="text-warning">Note:</span> This setting ONLY applies to <em>Recurring / Subscription</em> type payments.</div>

                        <div class="upfront-wrapper child-content display-none ignore-validation">

                            <div class="form-group">
                                <label>Upfront Amount<span class="required text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                    <input type="text" name="upfront_amount" class="form-control" placeholder="0.00" value="" data-fv-notempty="true"  data-fv-numeric="true">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Upfront Charge Description</label>
                                <input type="text" name="upfront_description" class="form-control" placeholder="Upfront Charge Description" value="{{ $form->fee_name or old('fee_name') }}">
                                <div class="form-text text-muted font-13">Let customers know what this upfront charge is, such as "Registration Fee".</div>
                            </div>

                            <div class="form-group">
                                <label>When to Charge Upfront Amount</label>

                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" name="upfront_charge_start" id="upfront_charge_start_immediately" value="immediately" checked>
                                    <label for="upfront_charge_start_immediately">Immediately Upon Submission</label>
                                </div>
                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" name="upfront_charge_start" id="upfront_charge_start_starts" value="starts">
                                    <label for="upfront_charge_start_starts">When Recurring Plan Starts</label>
                                </div>
                                <div class="form-text text-muted font-13">You can charge the upfront amount immediately upon form submission, or wait till the plan starts. (such as when a plan has a free trial period)</div>

                            </div>

                        </div>

                    </div>


                    <div class="controls">
                        <a href="#" class="btn btn-primary next-step"><i class="fa fa-angle-down"></i> Payment Frequency</a>
                        <a href="#" class="btn btn-outline-secondary previous-step">General Details <i class="fa fa-angle-up"></i></a>
                        <button type="submit" class="btn btn-outline-success save" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save & Finish</button>
                    </div>

                </div>

                <div class="form-step-content display-none" data-step="3">

                    <h4>Payment Frequency</h4>


                    <div class="form-group">
                        <label>Frequency Type</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-hide=".frequency-type-recurring, .frequency-type-customer-decide" name="frequency_type" id="frequency_type_one_time" value="one_time" checked>
                            <label for="frequency_type_one_time">One Time Payment</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".frequency-type-recurring" data-hide=".frequency-type-customer-decide" name="frequency_type" id="frequency_type_recurring" value="recurring">
                            <label for="frequency_type_recurring">Recurring / Subscription</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".frequency-type-customer-decide" data-hide=".frequency-type-recurring" name="frequency_type" id="frequency_type_customer_decide" value="customer_decide">
                            <label for="frequency_type_customer_decide">Let Customer Decide</label>
                        </div>

                        <div class="frequency-type-recurring child-content display-none ignore-validation">

                            <div class="form-group">
                                <label>How Are Recurring Options Determined</label>

                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" class="toggle-content" data-show=".recurring-options-set-now-wrapper" name="recurring_options" id="recurring_options_set_now" value="set_now" checked>
                                    <label for="recurring_options_set_now">Set Options Now</label>
                                </div>
                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" class="toggle-content" data-hide=".recurring-options-set-now-wrapper" name="recurring_options" id="recurring_options_customer_decide" value="customer_decide">
                                    <label for="recurring_options_customer_decide">Let Customer Decide</label>
                                </div>

                                <div class="recurring-options-set-now-wrapper child-content" data-ignore-validation="true">

                                    <div class="form-group">
                                        <label>Recurring Period</label>

                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="recurring_period_value" class="form-control">
                                                    @for ( $i = 1; $i <= 12; $i++ )
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-sm-10">
                                                <select name="recurring_period_term" class="form-control">
                                                    <option value="week">Week(s)</option>
                                                    <option value="month" selected>Month(s)</option>
                                                    <option value="year">Year(s)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-text text-muted font-13">Set the recurring period for how often payments will occur.</div>

                                    </div>

                                    <div class="form-group">
                                        <label>Subscription Start Date</label>

                                        <select name="recurring_start" class="form-control toggle-content">
                                            <option value="immediately" data-hide=".recurring-start-after-trial-wrapper, .recurring-start-fixed-date-wrapper, .recurring-start-day-month-wrapper">Immediately Upon Form Submission</option>
                                            <option value="after_trial" data-show=".recurring-start-after-trial-wrapper" data-hide=".recurring-start-fixed-date-wrapper, .recurring-start-day-month-wrapper">After a Trial Period</option>
                                            <option value="fixed_date" data-show=".recurring-start-fixed-date-wrapper" data-hide=".recurring-start-after-trial-wrapper, .recurring-start-day-month-wrapper">Set a Fixed Date</option>
                                            <option value="day_month" data-show=".recurring-start-day-month-wrapper" data-hide=".recurring-start-after-trial-wrapper, .recurring-start-fixed-date-wrapper">Specific Day of the Month</option>
                                            <option value="customer_decide" data-hide=".recurring-start-after-trial-wrapper, .recurring-start-fixed-date-wrapper, .recurring-start-day-month-wrapper">Let Customer Decide</option>
                                        </select>
                                        <div class="form-text text-muted font-13">Specify when you want the <em>Recurring  / Subscription</em> billing plan to begin.</div>

                                        <div class="recurring-start-after-trial-wrapper child-content display-none">

                                            <div class="form-group">
                                                <label>Trial Period</label>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <select name="recurring_start_after_trial_value" class="form-control">
                                                            @for ( $i = 1; $i <= 12; $i++ )
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <select name="recurring_start_after_trial_term" class="form-control">
                                                            <option value="week">Week(s)</option>
                                                            <option value="month" selected>Month(s)</option>
                                                            <option value="year">Year(s)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-text text-muted font-13">Billing will not begin until this length of time after form submission.</div>
                                            </div>

                                        </div>
                                        <div class="recurring-start-fixed-date-wrapper child-content display-none ignore-validation">

                                            <div class="form-group">
                                                <label>Start Subscription On<span class="required text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="date" name="recurring_start_fixed_date" class="form-control" placeholder="mm/dd/yyyy" value="" data-fv-notempty="true">
                                                </div>
                                                <div class="form-text text-muted font-13">Billing will not begin until this date.</div>
                                            </div>

                                        </div>
                                        <div class="recurring-start-day-month-wrapper child-content display-none">

                                            <div class="form-group">
                                                <label>Set Day of the Month</label>
                                                <select name="recurring_start_day_month" class="form-control">
                                                    @for ( $i = 1; $i <= 31; $i++ )
                                                        <option value="{{ $i }}">{{ ordinal($i) }}</option>
                                                    @endfor
                                                </select>
                                                <div class="form-text text-muted font-13">Billing will not begin until the next occurrence of this day of the month after form submission.</div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label>Duration</label>

                                        <div class="abc-radio abc-radio-primary radio-inline">
                                            <input type="radio" class="toggle-content" data-hide=".duration-fixed-wrapper" name="duration" id="duration_indefinite" value="indefinite" checked>
                                            <label for="duration_indefinite">Indefinite</label>
                                        </div>
                                        <div class="abc-radio abc-radio-primary radio-inline">
                                            <input type="radio" class="toggle-content" data-show=".duration-fixed-wrapper" name="duration" id="duration_fixed" value="fixed">
                                            <label for="duration_fixed">Fixed # of Billing Periods</label>
                                        </div>
                                        <div class="abc-radio abc-radio-primary radio-inline">
                                            <input type="radio" class="toggle-content" data-hide=".duration-fixed-wrapper" name="duration" id="duration_customer_decide" value="customer_decide">
                                            <label for="duration_customer_decide">Let Customer Decide</label>
                                        </div>
                                        <div class="form-text text-muted font-13">Specify how long you want the billing to last.</div>

                                        <div class="duration-fixed-wrapper child-content display-none ignore-validation">
                                            <div class="form-group">
                                                <label>Number of Billing Periods<span class="required text-danger">*</span></label>
                                                <input type="text" name="duration_fixed_periods" class="form-control" placeholder="0" value="{{ $form->duration_fixed_periods or old('duration_fixed_periods') }}" data-fv-notempty="true">
                                                <div class="form-text text-muted font-13">Specify the number of billing periods that a customer will be charged before the subscription is automatically canceled.</div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="frequency-type-customer-decide child-content display-none ignore-validation">

                            <div class="form-group">
                                <label>Default Frequency Option</label>

                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" name="default_frequency" id="default_frequency_one_time" value="one_time" checked>
                                    <label for="default_frequency_one_time">One Time Payment</label>
                                </div>
                                <div class="abc-radio abc-radio-primary radio-inline">
                                    <input type="radio" name="default_frequency" id="default_frequency_recurring" value="recurring">
                                    <label for="default_frequency_recurring">Recurring / Subscription</label>
                                </div>
                                <div class="form-text text-muted font-13">Select the default frequency option that customers will see.</div>

                            </div>

                        </div>

                    </div>


                    <div class="controls">
                        <a href="#" class="btn btn-primary next-step"><i class="fa fa-angle-down"></i> Additional Fields</a>
                        <a href="#" class="btn btn-outline-secondary previous-step">Amount / Fees <i class="fa fa-angle-up"></i></a>
                        <button type="submit" class="btn btn-outline-success save" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save & Finish</button>
                    </div>

                </div>

                <div class="form-step-content display-none" data-step="4">

                    <h4>Additional Fields</h4>

                    <div class="form-group">
                        <label>Gather Additional Information From Customers</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".additional-fields-wrapper" name="additional_fields" id="additional_fields_yes" value="yes">
                            <label for="additional_fields_yes">Yes</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-hide=".additional-fields-wrapper" name="additional_fields" id="additional_fields_no" value="no" checked>
                            <label for="additional_fields_no">No</label>
                        </div>
                        <div class="form-text text-muted font-13">Gather additional information from customers via custom fields when they submit your form.</div>

                        <div class="additional-fields-wrapper child-content display-none ignore-validation">

                            <div class="form-group">
                                <label>Custom Field(s)</label>

                                <div class="mb-3 card field-row show-after-clone label-right">
                                    <div class="card-header pb-0">

                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-3"><span class="required text-danger">*</span>Field Title</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="additional_fields_title[]" class="form-control" placeholder="Field Title" value="" data-fv-notempty="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-3">Description</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="additional_fields_description[]" class="form-control" placeholder="Field Description" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-3">Field Type</label>
                                                    <div class="col-sm-9">
                                                        <select name="additional_fields_type[]" class="form-control toggle-content">
                                                            <option value="text" data-hide=".additional-fields-field-type-values" selected>Single Line Text</option>
                                                            <option value="paragraph" data-hide=".additional-fields-field-type-values">Paragraph Text</option>
                                                            <option value="dropdown" data-show=".additional-fields-field-type-values">Dropdown Menu</option>
                                                            <option value="checkbox" data-show=".additional-fields-field-type-values">Checkbox Fields</option>
                                                            <option value="radio" data-show=".additional-fields-field-type-values">Radio Fields</option>
                                                            <option value="address" data-hide=".additional-fields-field-type-values">Address Fields</option>
                                                            <option value="upload" data-hide=".additional-fields-field-type-values">File Upload</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7">

                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-3">Is Required</label>
                                                    <div class="col-sm-9 form-control-static">
                                                        <div class="abc-radio abc-radio-primary radio-inline">
                                                            <input type="radio" name="additional_fields_make_required[0]" id="make_required_yes_0" value="yes">
                                                            <label for="make_required_yes_0">Yes</label>
                                                        </div>
                                                        <div class="abc-radio abc-radio-primary radio-inline">
                                                            <input type="radio" name="additional_fields_make_required[0]" id="make_required_no_0" value="no" data-default-checked="true" checked>
                                                            <label for="make_required_no_0">No</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="additional-fields-field-type-values display-none ignore-validation">
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-sm-3"><span class="required text-danger">*</span>Field Options</label>
                                                        <div class="col-sm-9">
                                                            <textarea class="form-control" name="additional_fields_options[]" rows="3" placeholder="Field Option Values" data-fv-notempty="true"></textarea>
                                                            <div class="form-text text-muted font-13">Type each field option on a separate line.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="delete-closest-field display-none show-after-clone">
                                            <a href="#" class="text-danger delete-closest" data-closest=".field-row"><i class="fa fa-trash-o fa-lg"></i></a>
                                        </div>

                                    </div>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-secondary text-primary clone-content" data-content=".field-row:last" data-insert-after=".field-row:last"><i class="fa fa-plus"></i> Add Field</a>

                            </div>

                        </div>

                    </div>

                    <div class="controls">
                        <a href="#" class="btn btn-primary next-step"><i class="fa fa-angle-down"></i> Success / Confirmation</a>
                        <a href="#" class="btn btn-outline-secondary previous-step">Payment Frequency <i class="fa fa-angle-up"></i></a>
                        <button type="submit" class="btn btn-outline-success save" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save & Finish</button>
                    </div>

                </div>

                <div class="form-step-content display-none" data-step="5">

                    <h4>Success / Confirmation</h4>

                    <div class="form-group">
                        <label>After Successful Form Submission</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".after-submission-message-wrapper" data-hide=".after-submission-url-wrapper" name="after_submission" id="after_submission_message" value="message" checked>
                            <label for="after_submission_message">Display a Message</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".after-submission-url-wrapper" data-hide=".after-submission-message-wrapper" name="after_submission" id="after_submission_url" value="url">
                            <label for="after_submission_url">Redirect to URL</label>
                        </div>

                        <div class="after-submission-message-wrapper child-content" data-ignore-validation="true">

                            <div class="form-group">
                                <label>Confirmation Page Message</label>
                                <textarea class="form-control" name="after_submission_message" rows="3" placeholder="Confirmation Page Message"></textarea>
                                <div class="form-text text-muted font-13">Optionally enter in your own custom message that will be shown to customers after form submission.</div>
                            </div>

                        </div>

                        <div class="after-submission-url-wrapper child-content display-none ignore-validation">

                            <div class="form-group">
                                <label>Redirect URL<span class="required text-danger">*</span></label>
                                <input type="text" name="after_submission_url" class="form-control" placeholder="" value="" data-fv-notempty="true">
                                <div class="form-text text-muted font-13">Enter the full URL that you want to send customers to after form submission, e.g. www.example.com</div>
                            </div>

                        </div>

                    </div>

                    <div class="form-group">
                        <label>Custom Email Message</label>
                        <textarea class="form-control" name="email_message" rows="3" placeholder="Custom Email Message"></textarea>
                        <div class="form-text text-muted font-13">Optionally enter in a custom message that will be shown within the Form Submission Success email.</div>
                    </div>

                    <div class="controls">
                        <a href="#" class="btn btn-primary next-step"><i class="fa fa-angle-down"></i> Theme / Layout</a>
                        <a href="#" class="btn btn-outline-secondary previous-step">Additional Fields <i class="fa fa-angle-up"></i></a>
                        <button type="submit" class="btn btn-outline-success save" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save & Finish</button>
                    </div>

                </div>

                <div class="form-step-content display-none" data-step="6">

                    <h4>Theme / Layout</h4>

                    <div class="form-group">
                        <label>Theme</label>
                        <select name="theme" class="form-control toggle-content">
                            <option value="default" selected>- Default Theme - </option>
                        </select>
                        <div class="form-text text-muted font-13">Select the theme to be used for this form.  You can create/manage themes <a href="{{ url('account/themes') }}" target="_blank">here</a>.</div>
                    </div>

                    <div class="form-group">
                        <label>Page Layout</label>

                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".layout-single-wrapper" data-hide=".layout-double-wrapper" name="layout" id="layout_single" value="single" checked>
                            <label for="layout_single"><i class="fa fa-window-maximize fa-lg"></i> Single Column</label>
                        </div>
                        <div class="abc-radio abc-radio-primary radio-inline">
                            <input type="radio" class="toggle-content" data-show=".layout-double-wrapper" data-hide=".layout-single-wrapper" name="layout" id="layout_double" value="double">
                            <label for="layout_double"><i class="fa fa-columns fa-lg"></i> Double Column</label>
                        </div>
                        <div class="form-text text-muted font-13">Choose which page layout that you want to use when displaying your form.</div>


                        <div class="layout-single-wrapper child-content" data-ignore-validation="true">

                            <div class="form-group">
                                <label>Arrange Components for Single Column Layout</label>

                                <div class="sortable-wrapper">
                                    <ul class="sortable-single">
                                        <li>
                                            <i class="fa fa-arrows text-muted"></i> Form Title
                                            <input type="hidden" name="components_single[]" value="title">
                                        </li>
                                        <li>
                                            <i class="fa fa-arrows text-muted"></i> Form Description
                                            <input type="hidden" name="components_single[]" value="description">
                                        </li>
                                        <li>
                                            <i class="fa fa-arrows text-muted"></i> Amount / Fees
                                            <input type="hidden" name="components_single[]" value="amount">
                                        </li>
                                        <li>
                                            <i class="fa fa-arrows text-muted"></i> Payment Frequency
                                            <input type="hidden" name="components_single[]" value="frequency">
                                        </li>
                                        <li>
                                            <i class="fa fa-arrows text-muted"></i> Additional Fields
                                            <input type="hidden" name="components_single[]" value="additional">
                                        </li>
                                        <li>
                                            <i class="fa fa-arrows text-muted"></i> Coupon Field
                                            <input type="hidden" name="components_single[]" value="coupon">
                                        </li>
                                        <li>
                                            <i class="fa fa-arrows text-muted"></i> Credit/Debit Card Input
                                            <input type="hidden" name="components_single[]" value="payment">
                                        </li>
                                        <li>
                                            <i class="fa fa-arrows text-muted"></i> Terms & Conditions Checkbox
                                            <input type="hidden" name="components_single[]" value="terms">
                                        </li>
                                        <li class="disabled text-muted">
                                            <i class="fa fa-arrows text-muted"></i> Submit Button
                                        </li>
                                    </ul>
                                </div>

                                <div class="form-text text-muted font-13">You can arrange the various form components in whichever order you'd like them to appear.</div>
                            </div>

                        </div>

                        <div class="layout-double-wrapper child-content display-none">

                            <div class="form-group">
                                <label>Arrange Components for Double Column Layout</label>

                                <div class="sortable-wrapper">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <ul class="sortable-double">
                                                <li>
                                                    <i class="fa fa-arrows text-muted"></i> Form Title
                                                    <input type="hidden" name="components_double[]" value="title">
                                                </li>
                                                <li>
                                                    <i class="fa fa-arrows text-muted"></i> Form Description
                                                    <input type="hidden" name="components_double[]" value="description">
                                                </li>
                                                <li>
                                                    <i class="fa fa-arrows text-muted"></i> Amount / Fees
                                                    <input type="hidden" name="components_double[]" value="amount">
                                                </li>
                                                <li>
                                                    <i class="fa fa-arrows text-muted"></i> Payment Frequency
                                                    <input type="hidden" name="components_double[]" value="frequency">
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="column-separator"></div>
                                            <ul class="sortable-double">
                                                <li>
                                                    <i class="fa fa-arrows text-muted"></i> Additional Fields
                                                    <input type="hidden" name="components_double[]" value="additional">
                                                </li>
                                                <li>
                                                    <i class="fa fa-arrows text-muted"></i> Coupon Field
                                                    <input type="hidden" name="components_double[]" value="coupon">
                                                </li>
                                                <li>
                                                    <i class="fa fa-arrows text-muted"></i> Credit/Debit Card Input
                                                    <input type="hidden" name="components_double[]" value="payment">
                                                </li>
                                                <li>
                                                    <i class="fa fa-arrows text-muted"></i> Terms & Conditions Checkbox
                                                    <input type="hidden" name="components_double[]" value="terms">
                                                </li>
                                                <li class="disabled text-muted">
                                                    <i class="fa fa-arrows text-muted"></i> Submit Button
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-text text-muted font-13">You can arrange the various form components in whichever order you'd like them to appear.  You may also change which column the form components appear in.</div>
                            </div>

                        </div>


                    </div>


                    <div class="controls">
                        <a href="#" class="btn btn-outline-secondary previous-step">Success / Confirmation <i class="fa fa-angle-up"></i></a>
                        <button type="submit" class="btn btn-success save" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save & Finish</button>
                    </div>

                </div>

            </div>
        </div>


    </form>

</div>

@endsection

@push('scripts')
<script src="{{ url('assets/js/modules/forms.js') }}"></script>
@endpush