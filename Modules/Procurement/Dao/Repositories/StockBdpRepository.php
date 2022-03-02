<?php

namespace Modules\Procurement\Dao\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Modules\Procurement\Dao\Models\Stock;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Notes;

class StockBdpRepository extends Stock implements CrudInterface
{
    public function dataRepository()
    {
        $list = Helper::dataColumn($this->datatable);
        return $this->select(DB::raw("1 as stock_id,branch_name,stock_sell,stock_expired,stock_product_id, product_name, sum(stock_qty) as stock_qty"))
            ->where('stock_sell', 0)
            ->joinRelationship('has_branch')
            ->joinRelationship('has_product')
            ->groupBy(['stock_product_id', 'stock_branch_id', 'stock_sell', 'stock_expired'])->orderBy('stock_expired');

        // return DB::table('view_summary_stock');
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
            return Notes::update($update->toArray());
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

    public function singleRepository($code, $relation = false)
    {
        if ($relation) {
            return $this->with($relation)->findOrFail($code);
        }
        return $this->findOrFail($code);
    }

    public function updateStock($branch_id, $product_id, $qty, $operator = 1)
    {
        $stock = $this->where($this->branch_id(), $branch_id)
            ->where($this->product_id(), $product_id)->first();
        if ($stock) {

            $old = $stock->qty;
            $stock->qty = $operator ? $old + $qty : $old - $qty;
            $stock->save();
        } else {
            $this->create([
                $this->branch_id() => $branch_id,
                $this->product_id() => $product_id,
                $this->qty() => $qty,
            ]);
        }
    }
}
