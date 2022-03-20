<?php

namespace Modules\Procurement\Http\Services;

use Modules\Procurement\Dao\Facades\DeDetailFacades;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Http\Services\UpdateService;
use Modules\System\Plugins\Alert;

class DeliveryUpdateService extends UpdateService
{
    public function update(CrudInterface $repository, $data, $code)
    {
        $check = $repository->updateRepository($data->all(), $code);
        DeDetailFacades::upsert($data['detail'], [
            DeDetailFacades::mask_do_code(),
            DeDetailFacades::mask_product_id(),
        ], [
            DeDetailFacades::mask_notes(),
            DeDetailFacades::mask_qty(),
            DeDetailFacades::mask_price(),
            DeDetailFacades::mask_total(),
        ]);

        if (isset($check['status']) && $check['status']) {
            Alert::update();
        } else {
            Alert::error($check['data']);
        }
        return $check;
    }
}
