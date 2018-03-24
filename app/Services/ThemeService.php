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

        $data['logo_image'] = $this->uploadImage('logo_image', $data['logo_image']);
        $data['background_image'] = $this->uploadImage('background_image', $data['background_image']);

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
        $theme = Theme::findOrFail($id);

        $logo_image = isset($data['delete_logo']) ? $this->deleteImage($theme['logo_image']) : $theme->logo_image;
        $background_image = isset($data['delete_background']) ? $this->deleteImage($theme['background_image']) : $theme->background_image;

        $logo_image = isset($data['logo_image']) ? $this->uploadImage('logo_image', $data['logo_image']) : $logo_image;
        $background_image = isset($data['background_image']) ? $this->uploadImage('background_image', $data['background_image']) : $background_image;

        $data['logo_image'] = $logo_image;
        $data['background_image'] = $background_image;

        // update the theme
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

    /**
     * Upload our logo and/or background images
     * @param $image
     * @param $data
     *
     * @return null
     * @throws \AppExcp
     */
    public function uploadImage($image, $file) {

        $filename = null;
        if ( !empty($file) && $file->isValid() ) {
            if ( $file->getClientSize() > 2097152 ) {
                throw new \AppExcp('Your ' . $image . ' image is too large.');
            }
            $filename = $file->store($image == 'logo_image' ? 'theme_logos' : 'theme_backgrounds', 'public');
        }

        return $filename;

    }

    /**
     * Delete an existing theme image
     * @param $image
     *
     * @return null
     */
    public function deleteImage($image) {
        \Storage::disk('public')->delete($image);
        return null;
    }


}