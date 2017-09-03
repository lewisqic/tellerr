<?php

namespace App\Services;

use App\Plan;

class PlanService extends BaseService
{

    /**
     * declare our services to be injected
     */
    protected $administratorService;


    /**
     * controller construct
     * @param AdministratorService $administrator_service
     */
    public function __construct(AdministratorService $administrator_service)
    {
        $this->administratorService = $administrator_service;
    }


    /**
     * create a new user record
     * @param  array  $data
     * @return array
     */
    public function create($data)
    {
        $result = \DB::transaction(function() use($data) {
            // create the user
            $user = User::create(array_only($data, ['type', 'first_name', 'last_name', 'email', 'password']));
            // create the relation table
            switch ( $user->type ) {
                case Administrator::USER_TYPE_ID:
                    $administrator = $this->administratorService->create([
                        'user_id' => $user->id
                    ]);
                    break;
                case Member::USER_TYPE_ID:
                    $member = $this->memberService->create([
                        'user_id' => $user->id
                    ]);
                    break;
            }
            // create remark setting record
            $setting = $this->remarkSettingService->create($user->id);
            // get auth user
            $auth_user = \Auth::findById($user->id);
            // assign permissions
            if ( !empty($data['permissions']) ) {
                foreach ( $data['permissions'] as $permission ) {
                    $auth_user->addPermission($permission);
                }
                $auth_user->save();
            }
            // assign plans
            if ( !empty($data['plans']) ) {
                foreach ( $data['plans'] as $plan_id ) {
                    $plan = \Auth::findRoleById($plan_id);
                    $plan->users()->attach($auth_user);
                }
            }
            return [
                'user' => $user
            ];
        });
        return $result['user'];
    }


    /**
     * update a user record
     * @param  array  $data
     * @return array
     */
    public function update($id, $data)
    {
        // get the user
        $user = User::findOrFail($id);
        if ( isset($data['permissions']) ) {
            $user->permissions = null;
        }
        $user->fill(array_only($data, ['first_name', 'last_name', 'email', 'password']));
        $user->save();
        // assign permissions
        if ( !empty($data['permissions']) ) {
            $auth_user = \Auth::findById($user->id);
            foreach ( $data['permissions'] as $permission ) {
                $auth_user->addPermission($permission);
            }
            $auth_user->save();
        }
        // assign plans
        if ( isset($data['plans']) ) {
            $user->plans()->sync($data['plans']);
        }
        return $user;
    }


    /**
     * return array of plans data for datatables
     * @return array
     */
    public function dataTables($data)
    {
        $plans = Plan::all();
        $plans_arr = [];
        foreach ( $plans as $plan ) {
            $plans_arr[] = [
                'id' => $plan->id,
                'class' => !is_null($plan->deleted_at) ? 'text-danger' : null,
                'name' => $plan->name,
                'is_default' => $plan->is_default ? 'Yes' : 'No',
                'created_at' => [
                    'display' => $plan->created_at->toFormattedDateString(),
                    'sort' => $plan->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url('admin/plans/' . $plan->id . '/edit'),
                    'delete' => url('admin/plans/' . $plan->id),
                    'click' => url('admin/plans/' . $plan->id)
                ])
            ];
        }
        return $plans_arr;
    }


}