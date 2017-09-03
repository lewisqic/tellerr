<?php

namespace App\Services;

use App\CompanyPaymentMethod;

class CompanyPaymentMethodService extends BaseService
{

    /**
     * create a new company payment method record
     * @param  array  $data [description]
     * @return array
     */
    public function create($data)
    {
        // create the payment method
        $cpm = CompanyPaymentMethod::create($data);
        return $cpm;
    }


    /**
     * update a company payment method record
     * @param  array  $data
     * @return array
     */
    public function update($id, $data)
    {
        // get the payment method record
        $cpm = CompanyPaymentMethod::findOrFail($id);
        $cpm->fill($data);
        $cpm->save();
        return $cpm;
    }


    /**
     * set default flag on payment method record
     * @param  int  $id
     * @return array
     */
    public function setDefault($id)
    {
        $company = app('app_user')->member->company;
        CompanyPaymentMethod::where('company_id', $company->id)->update(['is_default' => 0]);
        $data = ['is_default' => true];
        $company_payment_method  = $this->update($id, $data);
        return $company_payment_method;
    }

    /**
     * add a new credit/debit card payment method 
     * @param array $data
     * @throws \AppExcp
     * @return array
     */
    public function addNewCard($data)
    {
        $company = app('app_user')->member->company;

    	$result = \Braintree_PaymentMethod::create([
	        'customerId' => $company->braintree_customer_id,
	        'paymentMethodNonce' => $data['nonce'],
	        'options' => [
                'verifyCard' => true
	        ]
	    ]);

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

            $paymentMethod = $result->paymentMethod;
            $card_data = [
                'cc_token' => $paymentMethod->token,
                'cc_type' => $paymentMethod->cardType,
                'cc_last4' => $paymentMethod->last4,
                'cc_expiration_month' => $paymentMethod->expirationMonth,
                'cc_expiration_year' => $paymentMethod->expirationYear,
                'is_default' => false
            ];
        
            // save our payment method record
            $payment_method_data = [
                'company_id' => $company->id
            ];
            $company_payment_method = $this->create(array_merge($payment_method_data, $card_data));

            // set default method if requested
            if ( isset($data['is_default']) ) {
                $this->setDefault($company_payment_method->id);
            }

            return $company_payment_method;

        }

    }

    /**
     * check if a card is almost expired
     * @param  object $company_payment_method
     * @return bool                        
     */
    public function almostExpired($company_payment_method)
    {
        if ( $company_payment_method->cc_expiration_year == date('Y') && $company_payment_method->cc_expiration_month == date('m') ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if a card is expired
     * @param  object $company_payment_method
     * @return bool                        
     */
    public function isExpired($company_payment_method)
    {
        $expires_ts = strtotime('+1 month', strtotime($company_payment_method->cc_expiration_year . '-' . $company_payment_method->cc_expiration_month . '-01'));
        if ( $expires_ts < strtotime('yesterday midnight') ) {
            return true;
        } else {
            return false;
        }
    }

    
}
