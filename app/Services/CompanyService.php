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
        $company->fill(array_only($data, ['braintree_customer_id', 'name', 'email', 'subdomain', 'currency', 'language']));
        $company->save();
        return $company;
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
