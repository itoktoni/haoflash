<?php

namespace Modules\Procurement\Dao\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Procurement\Dao\Models\Stock;
use Modules\Procurement\Dao\Models\StockBdp;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Notes;

class StockBdpRepository extends Stock
{
    public function dataRepository()
    {
        $list = Helper::dataColumn($this->datatable);
        return $this->select($list)
            ->where('stock_type', CategoryType::BDP)
            ->joinRelationship('has_branch')
            ->joinRelationship('has_product')
            ->joinRelationship('has_supplier');

        // return DB::table('view_summary_stock');
    }

    public function updateRepository($request, $code)
    {
        try {
            $update = $this->where($this->mask_product_id(), $code)
                ->whereBetween($this->mask_code(), [$request->start, $request->end])
                ->update([
                    'stock_product_id' => $request->stock_product_id,
                    'stock_refer_product_id' => $request->stock_refer_product_id,
                    'stock_activated_at' => date('Y-m-d H:i:s'),
                    'stock_activated_by' => auth()->user()->id,
                    'stock_type' => CategoryType::Virtual,
                ]);
            return Notes::update($request->toArray());
        } catch (QueryException $ex) {
            return Notes::error($ex->getMessage());
        }
    }
}
