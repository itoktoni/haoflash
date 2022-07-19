<?php

namespace Modules\Procurement\Http\Services;

use Modules\Procurement\Dao\Enums\DeliveryStatus;
use Modules\Procurement\Dao\Facades\DeDetailFacades;
use Modules\Procurement\Dao\Facades\DeFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Http\Services\UpdateService;
use Modules\System\Plugins\Alert;

class DeliveryReceiveService extends UpdateService
{
    public function save($repository, $data)
    {
        $select = DeFacades::with(['has_stock_prepare'])->find($data->code);
        if ($select) {

            $stock = $select->has_stock_prepare()->where(StockFacades::mask_transfer(), 1)->update([
                StockFacades::mask_branch_id() => $data->do_branch_id,
                StockFacades::mask_transfer() => 0,
            ]);

            $select->update([
                DeFacades::mask_status() => DeliveryStatus::Receive
            ]);
        }
        DeDetailFacades::update($data->detail, [
            DeDetailFacades::mask_do_code(),
            DeDetailFacades::mask_key(),
        ], [
            DeDetailFacades::mask_qty(),
            DeDetailFacades::mask_total(),
            'do_detail_prepare',
            'do_detail_receive',
        ]);

        Alert::update();
        return $stock;
    }
}
