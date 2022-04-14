<?php

namespace Modules\System\Plugins;

use Illuminate\Support\Facades\DB;
use Modules\Procurement\Dao\Facades\DePrepareFacades;
use Modules\Procurement\Dao\Facades\PoReceiveFacades;
use Modules\Procurement\Dao\Models\Supplier;

class Adapter
{
    public static function getTotalStockPoProduct($code, $product)
    {
        return PoReceiveFacades::where(PoReceiveFacades::mask_po_code(), $code)->where(PoReceiveFacades::mask_product_id(), $product)->sum(PoReceiveFacades::mask_receive());
    }

    public static function getTotalStockDoProduct($code, $product, $supplier, $buy, $expired)
    {
        return DePrepareFacades::where(DePrepareFacades::mask_do_code(), $code)
            ->where(DePrepareFacades::mask_product_id(), $product)
            ->where(DePrepareFacades::mask_supplier_id(), $supplier)
            ->where(DePrepareFacades::mask_price(), $buy)
            ->where(DePrepareFacades::mask_expired(), $expired)
            ->sum(DePrepareFacades::mask_prepare());
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
