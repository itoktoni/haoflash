<?php

namespace Modules\System\Plugins;

use Modules\Procurement\Dao\Facades\DePrepareFacades;
use Modules\Procurement\Dao\Facades\PoReceiveFacades;

class Adapter
{
    public static function getTotalStockPoProduct($code, $product)
    {
        return PoReceiveFacades::where(PoReceiveFacades::mask_po_code(), $code)->where(PoReceiveFacades::mask_product_id(), $product)->sum(PoReceiveFacades::mask_receive());
    }

    public static function getTotalStockDoProduct($code, $product)
    {
        return DePrepareFacades::where(DePrepareFacades::mask_do_code(), $code)->where(DePrepareFacades::mask_key(), $product)->sum(DePrepareFacades::mask_prepare());
    }
}
