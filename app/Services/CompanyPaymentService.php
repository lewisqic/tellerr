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
                'id' => $company_payment->id,
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
     * charge a customers source
     * @param  array  $data
     * @throws \AppExcp
     * @return array
     */
    public function chargeCustomer($data)
    {

        // Charge the Customer instead of the card:
        $charge = \Stripe\Charge::create([
            'amount' => $data['amount'] * 100,
            'currency' => "USD",
            'customer' => $data['customer_id'],
            'source' => isset($data['source']) ? $data['source'] : null
        ]);

        return [
            'company_payment' => [
                'amount' => number_format($charge->amount / 100, 2),
                'currency' => $charge->currency,
                'stripe_charge_id' => $charge->id
            ],
            'company_payment_method' => [
                'stripe_source_id' => $charge->source->id,
                'cc_type' => $charge->source->brand,
                'cc_last4' => $charge->source->last4,
                'cc_expiration_month' => $charge->source->exp_month,
                'cc_expiration_year' => $charge->source->exp_year,
            ]
        ];

    }

    

    
}
