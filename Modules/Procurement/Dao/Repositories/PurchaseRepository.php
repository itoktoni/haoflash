<?php

namespace Modules\Procurement\Dao\Repositories;

use Illuminate\Database\QueryException;
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Models\Po;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Notes;

class PurchaseRepository extends Po implements CrudInterface
{
    public function dataRepository()
    {
        $list = Helper::dataColumn($this->datatable);
        return $this->select($list)
        ->leftJoinRelationship('has_user')
        ->leftJoinRelationship('has_supplier');
    }

    public function saveRepository($request)
    {
        try {
            $activity = $this->create($request);
            return Notes::create($activity);
        } catch (QueryException $ex) {
            return Notes::error($ex->getMessage());
        }
    }

    public function updateRepository($request, $code)
    {
        try {
            $update = $this->findOrFail($code);
            $update->update($request);
            return Notes::update($update);
        } catch (QueryException $ex) {
            return Notes::error($ex->getMessage());
        }
    }

    public function deleteRepository($request)
    {
        try {
            is_array($request) ? $this->destroy(array_values($request)) : $this->destroy($request);
            return Notes::delete($request);
        } catch (QueryException $ex) {
            return Notes::error($ex->getMessage());
        }
    }

    public function deleteTransactionRepository($master, $detail)
    {
        try {
            $check = PoDetailFacades::where(PoDetailFacades::mask_po_code(), $master)
                ->where(PoDetailFacades::mask_product_id(), $detail)->delete();

            $header = $this->find($master);

            if (!empty($header)) {

                $detail = $header->has_detail;
                if($detail->count()){
                    $header->mask_total = $detail->sum(PoDetailFacades::mask_total());
                    $header->save();
                }
                else{
                    $header->delete();
                }

            }

            if ($check) {
                return Notes::delete($detail);
            }

            return Notes::error('Failed Delete');
        } catch (QueryException $ex) {
            return Notes::error($ex->getMessage());
        }
    }

    public function singleRepository($code, $relation = false)
    {
        if ($relation) {
            return $this->with($relation)->findOrFail($code);
        }
        return $this->findOrFail($code);
    }
}
