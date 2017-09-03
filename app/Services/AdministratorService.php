<?php

namespace App\Services;

use App\User;
use App\Administrator;

class AdministratorService extends BaseService
{

    /**
     * controller construct
     */
    public function __construct()
    {

    }


    /**
     * create a new administrator record
     * @param  array  $data
     * @return array
     */
    public function create($data)
    {
        $administrator = Administrator::create($data);
        return $administrator;
    }


    /**
     * return array of administrator data for datatables
     * @return array
     */
    public function dataTables($data)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $administrators = User::queryByType(Administrator::USER_TYPE_ID, $trashed);
        $administrators->load('roles');
        $administrators_arr = [];
        foreach ( $administrators as $administrator ) {
            if ( isset($data['role_id']) && $administrator->roles->count() > 0 ) {
                $role_ids = array_pluck($administrator->roles->toArray(), 'id');
                if ( !in_array($data['role_id'], $role_ids) ) {
                    continue;
                }
            }
            $administrators_arr[] = [
                'id' => $administrator->id,
                'class' => !is_null($administrator->deleted_at) ? 'text-danger' : null,
                'first_name' => $administrator->first_name,
                'last_name' => $administrator->last_name,
                'email' => $administrator->email,
                'last_login' => [
                    'display' => $administrator->last_login ? $administrator->last_login->toFormattedDateString() : '',
                    'sort' => $administrator->last_login ? $administrator->last_login->timestamp : ''
                ],
                'created_at' => [
                    'display' => $administrator->created_at->toFormattedDateString(),
                    'sort' => $administrator->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url('admin/administrators/' . $administrator->id . '/edit'),
                    'delete' => url('admin/administrators/' . $administrator->id),
                    'restore' => !is_null($administrator->deleted_at) ? url('admin/administrators/' . $administrator->id) : null,
                    'click' => url('admin/administrators/' . $administrator->id)
                ])
            ];
        }
        return $administrators_arr;
    }


}