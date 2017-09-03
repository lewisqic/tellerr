<?php

namespace App\Http\Controllers\Account;

use App\Member;
use App\User;
use App\Role;
use App\Services\UserService;
use App\Services\MemberService;
use App\Http\Controllers\Controller;


class AccountUserController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $userService;
    protected $memberService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $user_service, MemberService $member_service)
    {
        $this->userService = $user_service;
        $this->memberService = $member_service;
    }

    /**
     * Show the administrators list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.account.users.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = $this->memberService->dataTables(\Request::all(), app('company')->id);
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
            'action' => url('account/users'),
            'permissions' => \Config::get('permissions')['account'],
            'roles' => Role::queryByType(Member::USER_TYPE_ID, app('company')->id)
        ];
        return view('content.account.users.create-edit', $data);
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
            'action' => url('account/users/' . $id),
            'permissions' => \Config::get('permissions')['account'],
            'roles' => Role::queryByType(Member::USER_TYPE_ID, app('company')->id),
            'user' => $user,
            'user_roles' => $user->roles->count() ? $user->roles->toArray() : [],
            'user_permissions' => $user->permissions ? json_decode($user->permissions, true) : []
        ];
        return view('content.account.users.create-edit', $data);
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
            'permissions' => \Config::get('permissions')['account'],
            'user_roles' => $user->roles->count() ? $user->roles->toArray() : [],
            'user_permissions' => $user->permissions ? json_decode($user->permissions, true) : []
        ];
        return view('content.account.users.show', $data);
    }

    /**
     * Create new administrator record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $data['type'] = Member::USER_TYPE_ID;
        $data['company_id'] = app('company')->id;
        $user = $this->userService->create($data);
        \Msg::success($user->name . ' has been added successfully!');
        return redir('account/users');
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
        return redir('account/users');
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
            \Msg::success($user->name . ' has been deleted successfully! ' . \Html::undoLink('account/users/' . $user->id));
        }
        return redir('account/users');
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
        return redir('account/users');
    }


}
