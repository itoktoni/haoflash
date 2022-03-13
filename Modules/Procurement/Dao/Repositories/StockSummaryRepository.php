<?php

namespace Modules\Procurement\Dao\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Procurement\Dao\Models\Stock;
use Modules\Procurement\Dao\Models\StockBdp;
use Modules\Procurement\Dao\Models\StockSummary;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Notes;

class StockSummaryRepository extends StockBdp
{
    public function dataRepository()
    {
        $list = Helper::dataColumn($this->datatable);
        return $this->select(DB::raw("1 as stock_id,branch_name,supplier_name,stock_buy,stock_expired,stock_product_id, product_name,product_description, sum(stock_qty) as stock_qty"))
            ->where('stock_type', CategoryType::Virtual)
            ->joinRelationship('has_branch')
            ->joinRelationship('has_product')
            ->joinRelationship('has_supplier')
            ->groupBy(['stock_product_id', 'stock_branch_id', 'stock_supplier_id','stock_buy', 'stock_expired'])->orderBy('stock_expired');
    }
}
