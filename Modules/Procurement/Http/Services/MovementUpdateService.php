<?php

namespace Modules\Procurement\Http\Services;

use Modules\Procurement\Dao\Facades\MovementDetailFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\Procurement\Dao\Models\MovementDetail;
use Modules\Procurement\Dao\Models\Stock;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Http\Services\UpdateService;
use Modules\System\Plugins\Alert;

class MovementUpdateService extends UpdateService
{
    public function update(CrudInterface $repository, $data, $code)
    {
        $check = $repository->updateRepository($data->all(), $code);
        MovementDetail::upsert($data['detail'], [
            MovementDetailFacades::detail_id(),
            MovementDetailFacades::product_id(),
        ], [
            MovementDetailFacades::qty(),
        ]);

        if (isset($check['status']) && $check['status']) {
            $data = $check['data'];
            if($data->flag == 2)
            {
                foreach($data['detail'] as $detail)
                {
                    StockFacades::updateStock($data->branch_from, $detail->product_id, $detail->qty, false);
                    StockFacades::updateStock($data->branch_to, $detail->product_id, $detail->qty);
                }

                $data->flag = 3;
                $data->save();
            }
        }

        if ($check['status']) {
            Alert::update();
        } else {
            Alert::error($check['data']);
        }
        return $check;
    }
}