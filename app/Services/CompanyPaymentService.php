<?php

namespace App\Services;

use App\Plan;
use App\CompanyPayment;
use App\StatusHistory;

class CompanyPaymentService extends BaseService
{

    /**
     * return array of plan data for datatables
     * @return array 
     */
    public function dataTables($data)
    {   
        $company_payments = app('company')->payments;
        $company_payments_arr = [];
        foreach ( $company_payments as $company_payment ) {
            $company_payments_arr[] = [
                'braintree_transaction_id' => $company_payment->braintree_transaction_id,
                'amount' => \Format::currency($company_payment->amount),
                'notes' => $company_payment->notes,
                'status' => $company_payment->status,
                'payment_method' => \Html::ccIcon($company_payment->paymentMethod->cc_type) . ' XXXX-' . $company_payment->paymentMethod->cc_last4,
                'created_at' => [
                    'display' => $company_payment->created_at->toFormattedDateString(),
                    'sort' => $company_payment->created_at->timestamp
                ]
            ];
        }
        return $company_payments_arr;
    }

	/**
     * create a new company payment record
     * @param  array  $data [description]
     * @return array
     */
    public function create($data)
    {
        // create the payment method
        $company_payment = CompanyPayment::create($data);
        // add status history record for payment
        StatusHistory::store($company_payment);
        return $company_payment;
    }


    /**
     * charge a new credit card
     * @param  array  $data
     * @throws \AppExcp
     * @return array
     */
    public function chargeCard($data, $user = null, $braintree_customer_id = null)
    {
        $company = $user ? $user->member->company : null;

        $sale_data = [
            'amount' => $data['amount'],
            'options' => [
                'storeInVaultOnSuccess' => true,
                'submitForSettlement' => true
            ]
        ];

        if ( !empty($data['token']) ) {
            $sale_data['paymentMethodToken'] = $data['token'];
        } else {
            $sale_data['paymentMethodNonce'] = $data['nonce'];
        }

        if ( $braintree_customer_id || (!is_null($user) && !is_null($company->braintree_customer_id)) ) {
            $sale_data['customerId'] = $braintree_customer_id ? $braintree_customer_id : $company->braintree_customer_id;
        } else {
            if ( !is_null($user) && is_null($company->braintree_customer_id) ) {
                $sale_data['customer'] = [
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'email' => $user->email
                ];
            }
        }

    	$result = \Braintree_Transaction::sale($sale_data);
        if ( !$result->success ) {
            $error = 'unknown cause';
            if ( $result->errors->deepSize() > 0 ) {
                $errors = [];
                foreach( $result->errors->deepAll() as $error ) {
                    $errors[] = $error->message;
                }
                $error = implode(', ', $errors);
            } else {
                $error = $result->message;
            }
            throw new \AppExcp($error);
        } else {
        	$transaction = $result->transaction;
        	return [
                'company' => [
                    'braintree_customer_id' => $transaction->customer['id']
                ],
        		'company_payment' => [
	        		'amount' => number_format($transaction->amount, 2),
	        		'currency' => $transaction->currencyIsoCode,
	        		'braintree_transaction_id' => $transaction->id
        		],
        		'company_payment_method' => [
        			'cc_token' => $transaction->creditCard['token'],
                    'cc_type' => $transaction->creditCard['cardType'],
	        		'cc_last4' => $transaction->creditCard['last4'],
	        		'cc_expiration_month' => $transaction->creditCard['expirationMonth'],
	        		'cc_expiration_year' => $transaction->creditCard['expirationYear']
        		]
        	];
        }

        
    }

    

    
}
