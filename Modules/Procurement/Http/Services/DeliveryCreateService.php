<?php

namespace Modules\Procurement\Http\Services;

use Modules\Procurement\Dao\Facades\DeDetailFacades;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Alert;

class DeliveryCreateService
{
    public function save(CrudInterface $repository, $data)
    {
        $check = false;
        try {
            $check = $repository->saveRepository($data->all());
            if(!empty($data['detail'])){
                DeDetailFacades::insert($data['detail']);
            }

            if(isset($check['status']) && $check['status']){

                Alert::create();
            }
            else{
                $message = env('APP_DEBUG') ? $check['data'] : $check['message'];
                Alert::error($message);
            }
        } catch (\Throwable $th) {
            Alert::error($th->getMessage());
            return $th->getMessage();
        }

        return $check;
    }
}
