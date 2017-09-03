<?php

namespace App\Services;

use App\User;
use App\Role;
use App\Administrator;

class RoleService extends BaseService
{


    /**
     * controller construct
     */
    public function __construct()
    {
    }


    /**
     * create a new role record
     * @param  array  $data
     * @return array
     */
    public function create($data)
    {
        $result = \DB::transaction(function() use($data) {
            // determine default
            if ( $data['is_default'] ) {
                Role::where('type', $data['type'])->where('company_id', $data['company_id'])->update(['is_default' => 0]);
            }
            // create the role
            $role = Role::create(array_only($data, ['type', 'company_id', 'name', 'is_default']));
            // assign permissions
            if ( !empty($data['permissions']) ) {
                $auth_role = \Auth::findRoleById($role->id);
                foreach ( $data['permissions'] as $permission ) {
                    $auth_role->addPermission($permission);
                }
                $auth_role->save();
            }
            return [
                'role' => $role
            ];
        });
        return $result['role'];
    }


    /**
     * update a role record
     * @param  array  $data
     * @return array
     */
    public function update($id, $data)
    {
        $result = \DB::transaction(function() use($id, $data) {
            // update the role
            $role = Role::findOrFail($id);
            $role->fill(array_only($data, ['name', 'is_default']));
            $role->permissions = null;
            $role->save();
            // remove defaults
            if ( $data['is_default'] ) {
                Role::where('id', '!=', $role->id)->where('type', $role->type)->where('company_id', $role->company_id)->update(['is_default' => 0]);
            }
            // assign permissions
            if ( !empty($data['permissions']) ) {
                $auth_role = \Auth::findRoleById($role->id);
                foreach ( $data['permissions'] as $permission ) {
                    $auth_role->addPermission($permission);
                }
                $auth_role->save();
            }
            return [
                'role' => $role
            ];
        });
        return $result['role'];
    }


    /**
     * delete a role
     * @param  int  $id
     * @return object
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $count = Role::where('type', $role->type)->where('company_id', $role->company_id)->count();
        if ( $role->users->count() > 0 ) {
            throw new \AppExcp('Roles with attached users cannot be deleted.');
        }
        if ( $count == 1 ) {
            throw new \AppExcp('You cannot delete the last role.');
        }
        $role->delete();
        if ( $count == 2 ) {
            Role::where('type', $role->type)->where('company_id', $role->company_id)->update(['is_default' => 1]);
        }
        return $role;
    }


    /**
     * return array of roles data for datatables
     * @return array
     */
    public function dataTables($data, $type, $company_id = null)
    {
        $roles = Role::queryByType($type, $company_id);
        $roles->load('users');
        $roles_arr = [];
        foreach ( $roles as $role ) {
            $roles_arr[] = [
                'id' => $role->id,
                'class' => !is_null($role->deleted_at) ? 'text-danger' : null,
                'name' => $role->name,
                'is_default' => $role->is_default ? 'Yes' : 'No',
                'user_count' => $role->users()->count(),
                'created_at' => [
                    'display' => $role->created_at->toFormattedDateString(),
                    'sort' => $role->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url(User::$types[$type]['route'] . '/roles/' . $role->id . '/edit'),
                    'delete' => url(User::$types[$type]['route'] . '/roles/' . $role->id),
                    'click' => url(User::$types[$type]['route'] . '/roles/' . $role->id)
                ])
            ];
        }
        return $roles_arr;
    }


}