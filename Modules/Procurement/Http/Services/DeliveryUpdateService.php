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
        if (!empty($data['detail'])) {
            // dd($data['detail']);
            foreach ($data['detail'] as $det) {
                // dump($det[DeDetailFacades::mask_product_id()]);
                if (!empty($det[DeDetailFacades::mask_product_id()]) && !empty($det[DeDetailFacades::mask_do_code()])) {
                    $save = DeDetailFacades::where(DeDetailFacades::mask_do_code(), $det[DeDetailFacades::mask_do_code()])
                    ->where(DeDetailFacades::mask_key(), $det[DeDetailFacades::mask_key()]);
                    if($save->count() > 0){
                        $save->update([
                            DeDetailFacades::mask_notes() => $det[DeDetailFacades::mask_notes()],
                            DeDetailFacades::mask_qty() => $det[DeDetailFacades::mask_qty()],
                            DeDetailFacades::mask_price() => $det[DeDetailFacades::mask_price()],
                            DeDetailFacades::mask_total() => $det[DeDetailFacades::mask_total()],
                        ]);
                    }
                    else{
                        DeDetailFacades::create($det);
                    }
                }
            }
        }
        // die();

        if (isset($check['status']) && $check['status']) {
            Alert::update();
        } else {
            Alert::error($check['data']);
        }
        return $check;
    }
}
