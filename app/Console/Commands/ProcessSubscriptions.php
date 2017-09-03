<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CompanySubscription;
use App\Services\CompanyPaymentService;
use App\Services\CompanyPaymentMethodService;
use App\Services\CompanySubscriptionService;

class ProcessSubscriptions extends Command
{

    /**
     * declare our services to be injected
     */
    protected $companyPaymentService;
    protected $companyPaymentMethodService;
    protected $companySubscriptionService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For active subscriptions, handle plan change requests, cancel subscriptions and charge subscription fees.';

    /**
     * Create a new command instance.
     *
 * @return void
     */
    public function __construct(CompanyPaymentService $company_payment_service, CompanyPaymentMethodService $company_payment_method_service, CompanySubscriptionService $company_subscription_service)
    {
        parent::__construct();
        $this->companyPaymentService = $company_payment_service;
        $this->companyPaymentMethodService = $company_payment_method_service;
        $this->companySubscriptionService = $company_subscription_service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->companySubscriptionService->processPendingCancelations();
        $this->companySubscriptionService->processPlanChangeRequests();
        $this->companySubscriptionService->processSubscriptionPayments();

    }
}
