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
        $data = $this->formService->dataTables(\Request::all());
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
        $form = Plan::findOrFail($id);
        $data = [
            'title' => 'Edit',
            'method' => 'put',
            'action' => url('account/forms/' . $id),
            'form' => $form,
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
        $form = Plan::findOrFail($id);
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
        $data = \Request::all();
        $form = $this->formService->create($data);
        \Msg::success($form->name . ' form has been added successfully!');
        return redir('account/forms');
    }

    /**
     * Create new form record
     *
     * @return redirect
     */
    public function update()
    {
        $form = $this->formService->update(\Request::input('id'), \Request::except('id'));
        \Msg::success($form->name . ' form has been updated successfully!');
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
        \Msg::success($form->name . ' form has been deleted successfully!');
        return redir('account/forms');
    }


}