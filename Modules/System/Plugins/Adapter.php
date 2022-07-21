<?php

namespace Modules\System\Plugins;

use Illuminate\Support\Facades\DB;
use Modules\Procurement\Dao\Facades\DePrepareFacades;
use Modules\Procurement\Dao\Facades\PoReceiveFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\Procurement\Dao\Models\Supplier;

class Adapter
{
    public static function getTotalStockPoProduct($code, $product)
    {
        return PoReceiveFacades::where(PoReceiveFacades::mask_po_code(), $code)->where(PoReceiveFacades::mask_product_id(), $product)->sum(PoReceiveFacades::mask_receive());
    }

    public static function getTotalStockDoProduct($code, $product, $supplier, $buy, $expired)
    {
        $query = DePrepareFacades::where(DePrepareFacades::mask_do_code(), $code)
            ->where(DePrepareFacades::mask_product_id(), $product)
            ->where(DePrepareFacades::mask_supplier_id(), $supplier)
            ->where(DePrepareFacades::mask_price(), $buy);
            if($expired != 0){
                $query->where(DePrepareFacades::mask_expired(), $expired);
            }
            return $query->sum(DePrepareFacades::mask_prepare());
    }

    public static function getTotalStockReceiveProduct($code, $product, $supplier, $branch, $buy, $expired)
    {
        $query = StockFacades::where(StockFacades::mask_primary_code(), $code)
            ->where(StockFacades::mask_product_id(), $product)
            ->where(StockFacades::mask_supplier_id(), $supplier)
            ->where(StockFacades::mask_branch_id(), $branch)
            ->where(StockFacades::mask_buy(), $buy);
            if($expired != 0){
                $query->where(StockFacades::mask_expired(), $expired);
            }
            return $query->where(StockFacades::mask_transfer(), 0)
            ->sum(StockFacades::mask_qty());
    }

    public static function getViewSummary($id)
    {
        return DB::table('view_summary_stock')->where('id', $id)->first() ?? false;
    }

    public static function getSupplierName($id)
    {
        return Supplier::find($id)->mask_name ?? '';
    }

    public static function splitKey($key)
    {
        return explode('_', $key);
    }
}
