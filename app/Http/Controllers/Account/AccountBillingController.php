<?php

namespace App\Http\Controllers\Account;

use App\User;
use App\Plan;
use App\Services\CompanyPaymentService;
use App\Services\CompanyPaymentMethodService;
use App\Services\CompanySubscriptionService;
use App\Http\Controllers\Controller;

class AccountBillingController extends Controller {

    /**
     * declare our services to be injected
     */
    protected $companyPaymentService;
    protected $companyPaymentMethodService;
    protected $companySubscriptionService;


	/**
     * controller construct
     *
     * @param CompanyPaymentMethodService $member_payment_service
     * @param CompanyPaymentMethodService $company_payment_method_service
     * @param CompanySubscriptionService $company_subscription_service
     */
    public function __construct(CompanyPaymentService $company_payment_service, CompanyPaymentMethodService $company_payment_method_service, CompanySubscriptionService $company_subscription_service)
    {
        $this->companyPaymentService = $company_payment_service;
        $this->companyPaymentMethodService = $company_payment_method_service;
        $this->companySubscriptionService = $company_subscription_service;
    }

    /**
     * show our subscription details page
     *
     * @return view
     */
    public function showSubscription()
    {
        $data = [
            'opposite_installment' => app('subscription')->installment == 'year' ? 'month' : 'year'
        ];
        return view('content.account.billing.subscription', $data);
    }

    /**
     * show our payment method page
     *
     * @return view
     */
    public function showPaymentMethods()
    {
        $payment_methods = app('company')->paymentMethods;
        $data = [
            'payment_methods' => $payment_methods,
            'default_payment_method' => $payment_methods->where('is_default', true)->first()
        ];
        return view('content.account.billing.payment-methods', $data);
    }

    /**
     * show our the billing history page
     *
     * @return view
     */
    public function showBillingHistory()
    {
        $data = [
        ];
        return view('content.account.billing.history', $data);
    }

    /**
     * return json data for company payments
     *
     * @return json
     */
    public function dataTables()
    {   
        $data = $this->companyPaymentService->dataTables(\Request::all());
        return response()->json($data);
    }

    /**
     * show our change plan page
     *
     * @return view
     */
    public function showChangePlan()
    {
        $plans = Plan::forPricingPage();
        $subscription = app('subscription');
        $current_plan = null;
        foreach ( $plans as $plan ) {
            if ( $plan->name == $subscription->plan_name && $plan->privilege == $subscription->plan_privilege ) {
                $current_plan = $plan;
            }
        }
        $payment_methods = [];
        foreach ( app('company')->paymentMethods as $payment_method ) {
            if ( !$this->companyPaymentMethodService->isExpired($payment_method) ) {
                $payment_methods[] = $payment_method;
            }
        }
        $data = [
            'plans' => Plan::forPricingPage(),
            'current_plan' => $current_plan,
            'payment_methods' => $payment_methods
        ];
        return view('content.account.billing.change-plan', $data);
    }

    /**
     * show our change subscription page
     *
     * @return view
     */
    public function showUpgradeSubscription()
    {
        $plans = Plan::forPricingPage();
        $subscription = app('subscription');
        if ( $subscription->status != 'trial' && $subscription->status != 'canceled' ) {
            return redir('account/billing/subscription');
        }
        $default_plan = $plans->first();
        foreach ( $plans as $plan ) {
            if ( $plan->name == $subscription->plan_name && $plan->privilege == $subscription->plan_privilege ) {
                $default_plan = $plan;
            }
        }
        $payment_methods = [];
        foreach ( app('company')->paymentMethods as $payment_method ) {
            if ( !$this->companyPaymentMethodService->isExpired($payment_method) ) {
                $payment_methods[] = $payment_method;
            }
        }
        $data = [
            'plans' => Plan::forPricingPage(),
            'default_plan' => $default_plan,
            'payment_methods' => $payment_methods
        ];
        return view('content.account.billing.upgrade', $data);
    }

    /**
     * handle our upgrade subscription request
     *
     * @return json
     */
    public function handleUpgradeSubscription()
    {
        $response = $this->companySubscriptionService->upgrade(\Request::all());
        \Msg::success('Thank you! Your payment has been processed and your subscription is now active. Enjoy!');
        return response()->json(['success' => true, 'route' => url('account/billing/subscription')]);
    }

    /**
     * handle our cancel subscription request
     *
     * @return redirect
     */
    public function handleCancelSubscription()
    {
        $response = $this->companySubscriptionService->cancel();
        \Msg::success('Your subscription has been canceled successfully.');
        return redir('account/billing/subscription');
    }

    /**
     * handle our resume subscription request
     *
     * @return redirect
     */
    public function handleResumeSubscription()
    {
        $response = $this->companySubscriptionService->resume();
        \Msg::success('Your subscription has been resumed successfully!');
        return redir('account/billing/subscription');
    }

    /**
     * change our subscription installment period
     *
     * @return redirect 
     */
    public function handleChangeInstallment()
    {
        $response = $this->companySubscriptionService->changeInstallment(\Request::all());
        \Msg::success('Your have changed your billing period successfully!');
        return redir('account/billing/subscription');
    }

    /**
     * handle our plan change request
     *
     * @return json
     */
    public function handleAddPaymentMethod()
    {
        $response = $this->companyPaymentMethodService->addNewSource(\Request::all());
        \Msg::success('Payment method has been added successfully!');
        return response()->json(['success' => true, 'route' => url('account/billing/payment-methods')]);
    }

    /**
     * handle our delete payment method request
     *
     * @param int $id
     * @return redirect
     */
    public function handleDeletePaymentMethod($id)
    {
        $response = $this->companyPaymentMethodService->destroy($id);
        \Msg::success('Payment method has been deleted successfully!');
        return redir('account/billing/payment-methods');
    }

    /**
     * handle our set defautl payment method request
     *
     * @param int $id
     * @return redirect
     */
    public function handleSetDefaultPaymentMethod($id)
    {
        $response = $this->companyPaymentMethodService->setDefault($id);
        \Msg::success('Payment method has been set to default successfully!');
        return redir('account/billing/payment-methods');
    }

    /**
     * handle our plan change request
     *
     * @return redirect
     */
    public function handleChangePlan()
    {
        $response = $this->companySubscriptionService->changePlan(\Request::all());
        \Msg::success($response['message']);
        return redir('account/billing/subscription');
    }

    /**
     * handle our cancel plan change request
     *
     * @return redirect
     */
    public function handleCancelPlanChange()
    {
        $response = $this->companySubscriptionService->cancelPlanChange();
        \Msg::success('Your plan change request has been canceled successfully!');
        return redir('account/billing/subscription');
    }

}
