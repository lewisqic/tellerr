<?php

namespace App\Services;

abstract class BaseService
{

    /**
     * delete a record
     *
     * @param  int  $id
     * @return object
     */
    public function destroy($id)
    {
        $service = get_called_class();
        $model = str_replace('App\Services\\', '', $service);
        $model = 'App\\' . str_replace('Service', '', $model);
        $record = $model::findOrFail($id);
        $record->delete();
        return $record;
    }


    /**
     * restore a record
     *
     * @param  int  $id
     * @return object
     */
    public function restore($id)
    {
        $service = get_called_class();
        $model = str_replace('App\Services\\', '', $service);
        $model = 'App\\' . str_replace('Service', '', $model);
        $record = $model::withTrashed()->findOrFail($id);
        $record->restore();
        return $record;
    }

}
