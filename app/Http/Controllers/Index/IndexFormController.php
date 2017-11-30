<?php

namespace App\Http\Controllers\Index;

use App\Form;
use App\Services\FormService;
use App\Mail\SignUpConfirmation;
use App\Http\Controllers\Controller;

class IndexFormController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $formService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FormService $fs)
    {
        $this->formService = $fs;
    }

    /**
     * Show the home page
     *
     * @return view
     */
    public function showForm($subdomain, $tld, $uniqie_id)
    {

        $form = Form::where('unique_id', $uniqie_id)->first();
        if ( is_null($form) ) {
            throw new \AppExcp('Invalid form ID');
        }

        $data = [
            'company' => $form->company,
            'form' => form_accessor($form)
        ];

        return view('content.index.forms.show', $data);
    }


}
