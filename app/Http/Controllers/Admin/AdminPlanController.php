<?php

namespace App\Http\Controllers\Admin;

use App\Plan;
use App\Services\PlanService;
use App\Http\Controllers\Controller;


class AdminPlanController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $planService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PlanService $plan_service)
    {
        $this->planService = $plan_service;
    }

    /**
     * Show the plans list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.admin.plans.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = $this->planService->dataTables(\Request::all());
        return response()->json($data);
    }

    /**
     * Show the plans create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title' => 'Add',
            'method' => 'post',
            'action' => url('admin/plans'),
        ];
        return view('content.admin.plans.create-edit', $data);
    }

    /**
     * Show the plans create page
     *
     * @return view
     */
    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        $data = [
            'title' => 'Edit',
            'method' => 'put',
            'action' => url('admin/plans/' . $id),
            'plan' => $plan,
        ];
        return view('content.admin.plans.create-edit', $data);
    }

    /**
     * Show an plan
     *
     * @return view
     */
    public function show($id)
    {
        $plan = Plan::findOrFail($id);
        $data = [
            'plan' => $plan,
        ];
        return view('content.admin.plans.show', $data);
    }

    /**
     * Create new plan record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $plan = $this->planService->create($data);
        \Msg::success($plan->name . ' plan has been added successfully!');
        return redir('admin/plans');
    }

    /**
     * Create new plan record
     *
     * @return redirect
     */
    public function update()
    {
        $plan = $this->planService->update(\Request::input('id'), \Request::except('id'));
        \Msg::success($plan->name . ' plan has been updated successfully!');
        return redir('admin/plans');
    }

    /**
     * Delete an plan record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $plan = $this->planService->destroy($id);
        \Msg::success($plan->name . ' plan has been deleted successfully!');
        return redir('admin/plans');
    }


}