<?php

namespace App\Http\Controllers\Admin;

use App\Administrator;
use App\User;
use App\Role;
use App\Services\UserService;
use App\Services\AdministratorService;
use App\Http\Controllers\Controller;


class AdminAdministratorController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $userService;
    protected $administratorService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $user_service, AdministratorService $administrator_service)
    {
        $this->userService = $user_service;
        $this->administratorService = $administrator_service;
    }

    /**
     * Show the administrators list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.admin.administrators.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = $this->administratorService->dataTables(\Request::all());
        return response()->json($data);
    }

    /**
     * Show the administrators create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title' => 'Add',
            'method' => 'post',
            'action' => url('admin/administrators'),
            'permissions' => \Config::get('permissions')['admin'],
            'roles' => Role::queryByType(Administrator::USER_TYPE_ID)
        ];
        return view('content.admin.administrators.create-edit', $data);
    }

    /**
     * Show the administrators create page
     *
     * @return view
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $data = [
            'title' => 'Edit',
            'method' => 'put',
            'action' => url('admin/administrators/' . $id),
            'permissions' => \Config::get('permissions')['admin'],
            'roles' => Role::queryByType(Administrator::USER_TYPE_ID),
            'user' => $user,
            'user_roles' => $user->roles->count() ? $user->roles->toArray() : [],
            'user_permissions' => $user->permissions ? json_decode($user->permissions, true) : []
        ];
        return view('content.admin.administrators.create-edit', $data);
    }

    /**
     * Show an administrator
     *
     * @return view
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $data = [
            'user' => $user,
            'permissions' => \Config::get('permissions')['admin'],
            'user_roles' => $user->roles->count() ? $user->roles->toArray() : [],
            'user_permissions' => $user->permissions ? json_decode($user->permissions, true) : []
        ];
        return view('content.admin.administrators.show', $data);
    }

    /**
     * Create new administrator record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $data['type'] = Administrator::USER_TYPE_ID;
        $user = $this->userService->create($data);
        \Msg::success($user->name . ' has been added successfully!');
        return redir('admin/administrators');
    }

    /**
     * Create new administrator record
     *
     * @return redirect
     */
    public function update()
    {
        $user = $this->userService->update(\Request::input('id'), \Request::except('id'));
        \Msg::success($user->name . ' has been updated successfully!');
        return redir('admin/administrators');
    }

    /**
     * Delete an administrator record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        if ( $id == app('app_user')->id ) {
            throw new \AppExcp('Currently logged in user cannot be deleted.');
        } else {
            $user = $this->userService->destroy($id);
            \Msg::success($user->name . ' has been deleted successfully! ' . \Html::undoLink('admin/administrators/' . $user->id));
        }
        return redir('admin/administrators');
    }

    /**
     * Restore an administrator record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $user = $this->userService->restore($id);
        \Msg::success($user->name . ' has been restored successfully!');
        return redir('admin/administrators');
    }


}
