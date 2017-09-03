<?php

namespace App\Services;

use App\User;
use App\Plan;
use App\PlanChangeRequest;
use App\CompanySubscription;
use App\CompanyPaymentMethod;
use App\StatusHistory;
use App\Services\CompanyService;
use App\Services\CompanyPaymentService;
use App\Services\CompanyPaymentMethodService;
use App\Mail\PaymentFailed;
use App\Mail\UpgradeConfirmation;
use App\Mail\PlanChangeConfirmation;
use App\Mail\CancelationConfirmation;


class CompanySubscriptionService extends BaseService
{

    /**
     * declare our services to be injected
     */
    protected $companyPaymentService;
    protected $companyPaymentMethodService;


    /**
     * controller construct
     *
     * @param CompanyPaymentService $member_payment_service
     */
    public function __construct(CompanyPaymentService $member_payment_service, CompanyPaymentMethodService $member_payment_method_service)
    {
        $this->companyPaymentService = $member_payment_service;
        $this->companyPaymentMethodService = $member_payment_method_service;
    }

    /**
     * create a new member subscription record
     *
     * @param  array $data [description]
     *
     * @throws \AppExcp
     * @return array
     */
    public function create($data)
    {
        // grab our plan
        $plan = Plan::find($data['plan_id']);
        if ( is_null($plan) ) {
            throw new \AppExcp('We were unable to complete your registration, please try again');
        }

        // create the company subsription
        $company_subscription = CompanySubscription::create([
            'company_id'       => $data['company_id'],
            'plan_privilege'   => $plan->privilege,
            'plan_name'        => $plan->name,
            'plan_price_month' => $plan->price_month,
            'plan_price_year'  => $plan->price_year,
            'status'           => 'trial',
            'trial_ends_at'    => \Carbon::now()->addDays(14),
        ]);
        // add status history record
        StatusHistory::store($company_subscription);

        return $company_subscription;
    }


    /**
     * update a company subscription record
     *
     * @param  array $data
     *
     * @return array
     */
    public function update($id, $data)
    {
        // get the payment method record
        $cpm = CompanySubscription::findOrFail($id);
        $cpm->fill($data);
        $cpm->save();
        return $cpm;
    }


    /**
     * update an existing member subscription record
     *
     * @param  array $data [description]
     *
     * @return array
     */
    public function updateAfterUpgrade($id, $plan, $installment)
    {
        // update the member subsription record
        $data = [
            'plan_privilege'   => $plan->privilege,
            'plan_name'        => $plan->name,
            'plan_price_month' => $plan->price_month,
            'plan_price_year'  => $plan->price_year,
            'amount'           => $installment == 'year' ? $plan->price_year : $plan->price_month,
            'installment'      => $installment,
            'status'           => 'active',
            'status_notes'     => null,
            'trial_ends_at'    => null,
            'canceled_at'      => null,
            'next_billing_at'  => $installment == 'year' ? \Carbon::now()->addYear() : \Carbon::now()->addMonth()
        ];
        $company_subscription = $this->update($id, $data);
        // add status history record
        StatusHistory::store($company_subscription);
        return $company_subscription;
    }


    /**
     * upgrade a subscription from trial to paid
     *
     * @param  array $data
     *
     * @return collection
     */
    public function upgrade($data)
    {
        $data = \DB::transaction(function () use ($data) {

            // grab company/subscription data
            $subscription = app('subscription');
            $user = app('app_user');
            $user->load('member.company');
            $company = $user->member->company;
            $type = $subscription->status == 'trial' ? 'new' : 'existing';

            // grab our selected plan
            $plan = Plan::find($data['plan_id']);
            if ( is_null($plan) ) {
                throw new \AppExcp('We were unable to complete your subscription upgrade, please try again');
            }

            // get our payment method token if we selected an exising card
            if ( isset($data['payment_method']) && $data['payment_method'] == 'existing' && !empty($data['company_payment_method_id']) ) {
                $payment_method = CompanyPaymentMethod::findOrFail($data['company_payment_method_id']);
            }
            $token = isset($payment_method) ? $payment_method->cc_token : '';

            // charge the card
            $amount = $data['installment'] == 'year' ? $plan->price_year : $plan->price_month;
            $charge_response = $this->companyPaymentService->chargeCard([
                'amount' => $amount,
                'nonce'  => $data['nonce'],
                'token'  => $token
            ], $user);

            // save our payment method record if it's a new one
            if ( !empty($data['nonce']) ) {
                CompanyPaymentMethod::where('company_id', $company->id)->update(['is_default' => 0]);
                $payment_method_data = [
                    'company_id' => $company->id,
                    'is_default' => 1
                ];
                $payment_method = $this->companyPaymentMethodService->create(array_merge($payment_method_data, $charge_response['company_payment_method']));
            }

            // save our payment record
            $payment_data = [
                'company_id'                => $company->id,
                'company_subscription_id'   => $subscription->id,
                'company_payment_method_id' => $payment_method->id,
                'notes'                     => $data['installment'] . 'ly subscription fee',
                'status'                    => 'complete'
            ];
            $payment = $this->companyPaymentService->create(array_merge($payment_data, $charge_response['company_payment']));

            // update company subscription record with new data
            $subscription = $this->updateAfterUpgrade($subscription->id, $plan, $data['installment']);

            // update company record with braintree customer id
            $company_service = app()->make('App\Services\CompanyService');
            $company_service->update($company->id, $charge_response['company']);

            // send confirmation email
            $mail_data = [
                'plan' => $plan->name,
                'amount' => \Format::currency($amount),
                'installment' => $data['installment'],
                'next_billing_date' => $data['installment'] == 'year' ? \Carbon::now()->addYear()->toFormattedDateString() : \Carbon::now()->addMonth()->toFormattedDateString(),
                'type' => $type
            ];
            \Mail::to($company->email)->send(new UpgradeConfirmation($mail_data));

            return [
                'payment'        => $payment,
                'payment_method' => $payment_method,
                'subscription'   => $subscription
            ];

        });
        return $data;
    }

    /**
     * cancel a subscription
     * @return obj
     */
    public function cancel()
    {
        $subscription = app('subscription');
        $data = [
            'status' => 'pending cancelation'
        ];
        $member_sub = $this->update($subscription->id, $data);
        // add status history record
        StatusHistory::store($member_sub);
        return $member_sub;
    }


    /**
     * resume a subscription
     * @return obj
     */
    public function resume()
    {
        $subscription = app('subscription');
        $data = [
            'canceled_at' => null,
            'status'      => 'active'
        ];
        $member_sub = $this->update($subscription->id, $data);
        // add status history record
        StatusHistory::store($member_sub);
        return $member_sub;
    }

    /**
     * change a subscription instlalment
     * @return obj
     */
    public function changeInstallment($data)
    {
        $subscription = app('subscription');
        $data = [
            'amount'      => $subscription->{'plan_price_' . $data['installment']},
            'installment' => $data['installment']
        ];
        $member_sub = $this->update($subscription->id, $data);
        return $member_sub;
    }

    /**
     * change a subscription plan
     * @return array
     */
    public function changePlan($data)
    {
        $subscription = app('subscription');
        $company = $subscription->company;
        $plan = Plan::findOrFail($data['plan_id']);

        // create plan change request if we are downgrading
        if ( $plan->privilege < $subscription->plan_privilege ) {
            PlanChangeRequest::create([
                'company_subscription_id' => $subscription->id,
                'plan_privilege'          => $plan->privilege,
                'plan_name'               => $plan->name,
                'plan_price_month'        => $plan->price_month,
                'plan_price_year'         => $plan->price_year,
            ]);
            $type = 'downgrade';
            $message = 'Your plan change request has been received and scheduled successfully!';
        }

        // charge card if new plan costs more than current plan
        if ( $plan->{'price_' . $subscription->installment} > $subscription->{'plan_price_' . $subscription->installment} ) {
            $payment_method = CompanyPaymentMethod::findOrFail($data['company_payment_method_id']);

            $charge_data = [
                'amount' => $plan->{'price_' . $subscription->installment} - $subscription->{'plan_price_' . $subscription->installment},
                'token'  => $payment_method->cc_token
            ];
            $charge_response = $this->companyPaymentService->chargeCard($charge_data, app('app_user'));

            // save our payment record
            $payment_data = [
                'company_id'                => $subscription->company->id,
                'company_subscription_id'   => $subscription->id,
                'company_payment_method_id' => $payment_method->id,
                'notes'                     => 'subscription plan upgrade fee',
                'status'                    => 'complete'
            ];
            $payment = $this->companyPaymentService->create(array_merge($payment_data, $charge_response['company_payment']));

            // update subscription plan details now
            $sub_data = [
                'plan_privilege'   => $plan->privilege,
                'plan_name'        => $plan->name,
                'plan_price_month' => $plan->price_month,
                'plan_price_year'  => $plan->price_year,
                'amount'           => $plan->{'price_' . $subscription->installment}
            ];
            $company_subscription = $this->update($subscription->id, $sub_data);

            $type = 'upgrade';
            $message = 'Your subscription plan has been changed successfully!';

        }

        // send confirmation email
        $mail_data = [
            'plan' => $plan->name,
            'amount' => \Format::currency($plan->{'price_' . $subscription->installment}),
            'installment' => $subscription->installment,
            'next_billing_date' => $subscription->next_billing_at->toFormattedDateString(),
            'type' => $type
        ];
        \Mail::to($company->email)->send(new PlanChangeConfirmation($mail_data));

        return ['message' => $message];

    }

    /**
     * change a subscription plan
     * @return array
     */
    public function cancelPlanChange()
    {

        $subscription = app('subscription');
        if ( $subscription->planChangeRequest ) {
            $subscription->planChangeRequest->delete();
        }

    }

    /**
     * cancel all pending cancelation subscriptions
     */
    public function processPendingCancelations()
    {

        $subscriptions = CompanySubscription::where('next_billing_at', \Carbon::now()->format('Y-m-d'))
            ->where('status', 'pending cancelation')
            ->get();

        foreach ( $subscriptions as $subscription ) {

            // update subscription data
            $subscription->status = 'canceled';
            $subscription->status_notes = 'Your subscription was manually canceled.';
            $subscription->next_billing_at = null;
            $subscription->canceled_at = \Carbon::now();
            $subscription->save();

            // add status history record
            StatusHistory::store($subscription);

            // send cancelation confirmation email
            $data = [
                'cancelation_date' => \Carbon::now()->toFormattedDateString()
            ];
            \Mail::to($subscription->company->email)->send(new CancelationConfirmation($data));

        }

    }

    /**
     * complete the plan change requests
     */
    public function processPlanChangeRequests()
    {

        $subscriptions = CompanySubscription::with('planChangeRequest')
            ->where('next_billing_at', \Carbon::now()
                ->format('Y-m-d'))
            ->get();

        foreach ( $subscriptions as $subscription ) {

            if ( !is_null($subscription->planChangeRequest) ) {
                $plan = $subscription->planChangeRequest;
                // update subscription
                $subscription->plan_privilege = $plan->plan_privilege;
                $subscription->plan_name = $plan->plan_name;
                $subscription->plan_price_month = $plan->plan_price_month;
                $subscription->plan_price_year = $plan->plan_price_year;
                $subscription->amount = $plan->{'plan_price_' . $subscription->installment};
                $subscription->save();
                // delete change request now
                $subscription->planChangeRequest->delete();
            }

        }


    }

    /**
     * change a subscription plan
     */
    public function processSubscriptionPayments()
    {

        $subscriptions = CompanySubscription::with(['company.paymentMethods' => function ($q) {
            $q->where('is_default', true);
        }
        ])->where('next_billing_at', '<=', \Carbon::now()->format('Y-m-d'))
            ->where('status', 'active')
            ->take(10)
            ->get();

        foreach ( $subscriptions as $subscription ) {
            try {

                // get default payment method
                $company = $subscription->company;
                $payment_method = $company->paymentMethods->first();
                if ( is_null($payment_method) ) {
                    throw new \AppExcp('No default payment method found');
                }
                $cc_token = $payment_method->cc_token;

                // charge credit card
                $charge_data = [
                    'amount' => $subscription->amount,
                    'token'  => $payment_method->cc_token
                ];
                $charge_response = $this->companyPaymentService->chargeCard($charge_data, null, $company->braintree_customer_id);

                // save our payment record
                $payment_data = [
                    'company_id'                => $company->id,
                    'company_subscription_id'   => $subscription->id,
                    'company_payment_method_id' => $payment_method->id,
                    'notes'                     => $subscription->installment . 'ly subscription fee',
                    'status'                    => 'complete'
                ];
                $payment = $this->companyPaymentService->create(array_merge($payment_data, $charge_response['company_payment']));

                // update subscription record
                $subscription->next_billing_at = $subscription->installment == 'year' ? \Carbon::now()->addYear() : \Carbon::now()->addMonth();
                $subscription->save();

            } catch ( \AppExcp $e ) {

                // update subscription data
                $subscription->status = 'canceled';
                $subscription->status_notes = 'Your subscription was canceled due to payment failure.';
                $subscription->next_billing_at = null;
                $subscription->canceled_at = \Carbon::now();
                $subscription->save();
                // add status history record
                StatusHistory::store($subscription);

                // send payment failed notification email
                $data = [
                    'amount' => \Format::currency($subscription->amount),
                    'date' => \Carbon::now()->toFormattedDateString()
                ];
                \Mail::to($company->email)->send(new PaymentFailed($data));


            }
        }

    }


}
