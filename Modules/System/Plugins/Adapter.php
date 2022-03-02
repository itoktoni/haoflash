<?php

namespace Modules\System\Plugins;

use Modules\Procurement\Dao\Facades\PoReceiveFacades;

class Adapter
{
    public static function getTotalStockPoProduct($po, $product){

        return PoReceiveFacades::where(PoReceiveFacades::mask_po_code(), $po)->where(PoReceiveFacades::mask_product_id(), $product)->sum(PoReceiveFacades::mask_receive());
    }
}
