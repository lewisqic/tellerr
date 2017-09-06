<?php

namespace App\Services;

use App\Company;
use App\Member;
use App\Role;
use App\Services\CompanySubscriptionService;

class CompanyService extends BaseService
{

    /**
     * declare our services to be injected
     */
    protected $companySubscriptionService;

    /**
     * controller construct
     */
    public function __construct(CompanySubscriptionService $company_subscription_service)
    {
        $this->companySubscriptionService = $company_subscription_service;
    }

    /**
     * create a new company record
     * @param  array  $data [description]
     * @return array
     */
    public function create($data)
    {
        // create the company
        $company_data = array_only($data, ['subdomain']);
        $company_data['email'] = $data['email'];
        $company_data['currency'] = Company::DEFAULT_CURRENCY;
        $company_data['language'] = Company::DEFAULT_LANGUAGE;
        $company = Company::create($company_data);

        // get all account permissions routes
        $all_routes = [];
        foreach ( \Config::get('permissions')['account'] as $group ) {
            foreach ( $group['actions'] as $key => $label ) {
                $all_routes[] = $group['controller'] . '@' . $key;
            }
        }
        // create the default role
        $role = Role::create([
            'type' => Member::USER_TYPE_ID,
            'company_id' => $company->id,
            'name' => Role::DEFAULT_ACCOUNT_ROLE_NAME,
            'is_default' => 1
        ]);
        // assign permissions
        if ( !empty($all_routes) ) {
            $auth_role = \Auth::findRoleById($role->id);
            foreach ( $all_routes as $permission ) {
                $auth_role->addPermission($permission);
            }
            $auth_role->save();
        }
        
        // create the company subscription
        $company_subscription = $this->companySubscriptionService->create([
            'company_id' => $company->id,
            'plan_id' => $data['plan_id']
        ]);

        return [$company, $role];

    }

    /**
     * update an existing company record
     * @param  array  $data
     * @return array
     */
    public function update($id, $data)
    {
        // get the user
        $company = Company::findOrFail($id);
        $company->fill(array_only($data, ['stripe_customer_id', 'stripe_account_id', 'stripe_secret_key', 'stripe_publishable_key', 'name', 'email', 'subdomain', 'currency', 'language', 'setup_completed', 'stripe_account_status']));
        $company->save();
        return $company;
    }

    /**
     * create a stripe customer record
     * @param  array  $data [description]
     * @return array
     */
    public function createStripeCustomer($data)
    {

        $customer = \Stripe\Customer::create([
            'description' => $data['name'],
            'email' => $data['email'],
            'source' => $data['token']
        ]);

        return $customer;

    }

    /**
     * create a stripe account
     * @param  array  $data [description]
     * @return array
     */
    public function createStripeAccount($company_id, $data)
    {

        $account = \Stripe\Account::create(array(
            'type' => 'standard',
            'country' => $data['country'],
            'email' => $data['email']
        ));

        $company = $this->update($company_id, [
           'stripe_account_id' => $account->id,
           'stripe_secret_key' => $account->keys->secret,
           'stripe_publishable_key' => $account->keys->publishable,
           'stripe_account_status' => 'deferred'
        ]);

        return $company;

    }

    /**
     * connect a stripe account
     * @param  array  $data [description]
     * @return array
     */
    public function connectStripeAccount($company_id, $data)
    {


        $token_request_body = [
            'grant_type' => 'authorization_code',
            'client_id' => env('STRIPE_CLIENT_ID'),
            'code' => $data['code'],
            'client_secret' => env('STRIPE_SECRET_KEY')
        ];
        $req = curl_init('https://connect.stripe.com/oauth/token');
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_POST, true );
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
        $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
        $response = json_decode(curl_exec($req), true);
        curl_close($req);

        if ( !empty($response['error_description']) ) {

            return [
                'success' => false,
                'message' => $response['error_description']
            ];

        } else {

            $company = $this->update($company_id, [
                'stripe_account_id' => $response['stripe_user_id'],
                'stripe_secret_key' => $response['access_token'],
                'stripe_publishable_key' => $response['stripe_publishable_key'],
                'setup_completed' => true,
                'stripe_account_status' => 'active'
            ]);

            return [
                'success' => true,
                'company' => $company
            ];

        }


    }

    /**
     * check if a subdomain already exists or not
     * @param  string $subdomain
     * @throws \AppExcp
     * @return null
     */
    public function validateSubdomain($subdomain)
    {
        $company = Company::findBySubdomain($subdomain);
        if ( empty($subdomain) || !is_null($company) ) {
            throw new \AppExcp('That name is already taken, please try a different one.');
        }
    }

    /**
     * check sign up form data for valid honeypot
     * @param  array $data
     * @throws \AppExcp
     * @return null
     */
    public function honeypotCheck($data)
    {
        if (
            (empty($data['init_ts']) || $data['init_ts'] > time() - 5) ||
            (empty($data['subdomain-validation']) || $data['subdomain-validation'] != 'human') ||
            (empty($data['address']) || $data['address'] != 'adddresss') ||
            (!empty($data['middle_name']))
        ) {
            throw new \AppExcp('Uh oh, not sure if you are human or not, please try again!');
        }
    }
    

    
}
