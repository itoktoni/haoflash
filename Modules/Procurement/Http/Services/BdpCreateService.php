<?php

namespace Modules\Procurement\Http\Services;

use Modules\Item\Dao\Enums\CategoryType;
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Alert;

class BdpCreateService
{
    public function save($repository, $data)
    {
        $check = false;
        try {

            $check = $repository->updateRepository($data, $data->stock_refer_product_id);

            if (isset($check['status']) && $check['status']) {

                Alert::create();
            } else {
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
