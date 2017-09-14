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
     * @return array
     */
    public function create($data)
    {

    }


    /**
     * update a form record
     * @param  array  $data
     * @return array
     */
    public function update($id, $data)
    {

    }


    /**
     * return array of forms data for datatables
     * @return array
     */
    public function dataTables($data)
    {
        $forms = Form::all();
        $forms_arr = [];
        foreach ( $forms as $plan ) {
            $forms_arr[] = [
                'id' => $plan->id,
                'class' => !is_null($plan->deleted_at) ? 'text-danger' : null,
                'name' => $plan->name,
                'is_default' => $plan->is_default ? 'Yes' : 'No',
                'created_at' => [
                    'display' => $plan->created_at->toFormattedDateString(),
                    'sort' => $plan->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url('admin/forms/' . $plan->id . '/edit'),
                    'delete' => url('admin/forms/' . $plan->id),
                    'click' => url('admin/forms/' . $plan->id)
                ])
            ];
        }
        return $forms_arr;
    }


}