<?php

namespace App\Services;

use App\User;
use App\Member;

class MemberService extends BaseService
{

    /**
     * controller construct
     */
    public function __construct()
    {

    }


    /**
     * create a new member record
     * @param  array  $data
     * @return array
     */
    public function create($data)
    {
        $member = Member::create($data);
        return $member;
    }


    /**
     * return array of member data for datatables
     * @return array
     */
    public function dataTables($data, $company_id = null)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $members = User::queryByType(Member::USER_TYPE_ID, $trashed, $company_id);
        $members->load('roles');
        $members_arr = [];
        foreach ( $members as $member ) {
            if ( isset($data['role_id']) && $member->roles->count() > 0 ) {
                $role_ids = array_pluck($member->roles->toArray(), 'id');
                if ( !in_array($data['role_id'], $role_ids) ) {
                    continue;
                }
            }
            $members_arr[] = [
                'id' => $member->id,
                'class' => !is_null($member->deleted_at) ? 'text-danger' : null,
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'email' => $member->email,
                'roles' => implode(', ', array_pluck($member->roles->toArray(), 'name')),
                'last_login' => [
                    'display' => $member->last_login ? $member->last_login->toFormattedDateString() : '',
                    'sort' => $member->last_login ? $member->last_login->timestamp : ''
                ],
                'created_at' => [
                    'display' => $member->created_at->toFormattedDateString(),
                    'sort' => $member->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url((is_null($company_id) ? 'admin/members/' : 'account/users/') . $member->id . '/edit'),
                    'delete' => url((is_null($company_id) ? 'admin/members/' : 'account/users/') . $member->id),
                    'restore' => !is_null($member->deleted_at) ? url((is_null($company_id) ? 'admin/members/' : 'account/users/') . $member->id) : null,
                    'click' => url((is_null($company_id) ? 'admin/members/' : 'account/users/') . $member->id)
                ])
            ];
        }
        return $members_arr;
    }


}