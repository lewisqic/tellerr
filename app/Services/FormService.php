<?php

namespace App\Services;

use App\Form;

class FormService extends BaseService
{

    /**
     * declare our services to be injected
     */


    /**
     * controller construct
     */
    public function __construct()
    {

    }


    /**
     * create a new form record
     * @param  array  $data
     */
    public function create($data)
    {
        // create the form
        $form = Form::create(form_mutator($data));
        return $form;
    }


    /**
     * update a form record
     * @param  array  $data
     */
    public function update($id, $data)
    {
        // update the role
        $form = Form::findOrFail($id);
        $form->fill(form_mutator($data));
        $form->save();
        return $form;
    }


    /**
     * return array of forms data for datatables
     * @return array
     */
    public function dataTables($data, $company_id)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $forms = Form::queryByCompany($company_id, $trashed);
        $forms_arr = [];
        foreach ( $forms as $form ) {
            $forms_arr[] = [
                'id' => $form->id,
                'class' => !is_null($form->deleted_at) ? 'text-danger' : null,
                'title' => $form->title,
                'created_at' => [
                    'display' => $form->created_at->toFormattedDateString(),
                    'sort' => $form->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url('account/forms/' . $form->id . '/edit'),
                    'disable_sidebar' => true,
                    'delete' => url('account/forms/' . $form->id),
                    'restore' => !is_null($form->deleted_at) ? url('account/forms/' . $form->id) : null,
                    'click' => url('account/forms/' . $form->id)
                ])
            ];
        }
        return $forms_arr;
    }


}