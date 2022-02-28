<?php

namespace Modules\Procurement\Http\Services;

use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Facades\PurchaseDetailFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\Procurement\Dao\Models\PurchaseDetail;
use Modules\Procurement\Dao\Models\Stock;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Http\Services\UpdateService;
use Modules\System\Plugins\Alert;

class PurchaseUpdateService extends UpdateService
{
    public function update(CrudInterface $repository, $data, $code)
    {
        $check = $repository->updateRepository($data->all(), $code);
        PoDetailFacades::upsert($data['detail'], [
            PoDetailFacades::mask_po_code(),
            PoDetailFacades::mask_product_id(),
        ], [
            PoDetailFacades::mask_qty(),
            PoDetailFacades::mask_price(),
            PoDetailFacades::mask_total(),
        ]);

        if (isset($check['status']) && $check['status']) {
            Alert::update();
        } else {
            Alert::error($check['data']);
        }
        return $check;
    }
}
