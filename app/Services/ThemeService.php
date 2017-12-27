<?php

namespace App\Services;

use App\Theme;

class ThemeService extends BaseService
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
     * create a new theme record
     * @param  array  $data
     */
    public function create($data)
    {
        // create the theme
        $theme = Theme::create($data);
        return $theme;
    }


    /**
     * update a theme record
     * @param  array  $data
     */
    public function update($id, $data)
    {
        // update the role
        $theme = Theme::findOrFail($id);
        $theme->fill($data);
        $theme->save();
        return $theme;
    }


    /**
     * return array of themes data for datatables
     * @return array
     */
    public function dataTables($data, $company_id)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $themes = Theme::queryByCompany($company_id, $trashed);
        $themes_arr = [];
        foreach ( $themes as $theme ) {
            $themes_arr[] = [
                'id' => $theme->id,
                'class' => !is_null($theme->deleted_at) ? 'text-danger' : null,
                'name' => $theme->name,
                'created_at' => [
                    'display' => $theme->created_at->toFormattedDateString(),
                    'sort' => $theme->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url('account/themes/' . $theme->id . '/edit'),
                    'delete' => url('account/themes/' . $theme->id),
                    'restore' => !is_null($theme->deleted_at) ? url('account/themes/' . $theme->id) : null,
                    'click' => url('account/themes/' . $theme->id)
                ])
            ];
        }
        return $themes_arr;
    }


}