<?php

namespace App\Http\Controllers\Account;

use App\Form;
use App\Services\FormService;
use App\Http\Controllers\Controller;


class AccountFormController extends Controller
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
    public function __construct(FormService $form_service)
    {
        $this->formService = $form_service;
    }

    /**
     * Show the forms list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.account.forms.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = $this->formService->dataTables(\Request::all(), app('company')->id);
        return response()->json($data);
    }

    /**
     * Show the forms create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title' => 'Create',
            'method' => 'post',
            'action' => url('account/forms'),
            'form' => null
        ];
        return view('content.account.forms.create-edit', $data);
    }

    /**
     * Show the forms create page
     *
     * @return view
     */
    public function edit($id)
    {
        $form = Form::findOrFail($id);
        $form = form_accessor($form);
        $data = [
            'title' => 'Edit',
            'method' => 'put',
            'action' => url('account/forms/' . $id),
            'form' => $form,
            'component_name_map' => array_merge(Form::$componentNameMap['left'], Form::$componentNameMap['right'])
        ];
        return view('content.account.forms.create-edit', $data);
    }

    /**
     * Show an form
     *
     * @return view
     */
    public function show($id)
    {
        $form = Form::findOrFail($id);
        $data = [
            'form' => $form,
        ];
        return view('content.account.forms.show', $data);
    }

    /**
     * Create new form record
     *
     * @return redirect
     */
    public function store()
    {
        $data = array_except(\Request::all(), ['_token', '_method']);
        $data['company_id'] = app('company')->id;
        $data['unique_id'] = uniqid();
        $data['status'] = 'active';
        $form = $this->formService->create($data);
        \Msg::success('Payment form has been created successfully!');
        return redir('account/forms');
    }

    /**
     * Create new form record
     *
     * @return redirect
     */
    public function update($id)
    {
        $data = array_except(\Request::all(), ['_token', '_method']);
        $form = $this->formService->update($id, $data);
        \Msg::success($form->title . ' form has been updated successfully!');
        return redir('account/forms');
    }

    /**
     * Delete an form record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $form = $this->formService->destroy($id);
        \Msg::success($form->title . ' form has been deleted successfully! ' . \Html::undoLink('account/forms/' . $form->id));
        return redir('account/forms');
    }

    /**
     * Restore a form record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $form = $this->formService->restore($id);
        \Msg::success($form->title . ' has been restored successfully!');
        return redir('account/forms');
    }


}