<?php

namespace Modules\Procurement\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Kirschbaum\PowerJoins\PowerJoins;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Models\Product;
use Modules\Procurement\Dao\Facades\BranchFacades;
use Modules\Procurement\Dao\Facades\SupplierFacades;
use Modules\System\Plugins\Helper;

class StockSummary extends Stock
{
    public $datatable = [
        'stock_id' => [false => 'Code', 'width' => 50],
        'stock_branch_id' => [false => 'Code', 'width' => 50],
        'supplier_name' => [true => 'Supplier', 'width' => 100],
        'branch_name' => [true => 'Branch', 'width' => 100],
        'product_name' => [true => 'Product'],
        'product_description' => [true => 'Product Description'],
        'stock_qty' => [true => 'Qty', 'width' => 50],
        'stock_buy' => [true => 'Price','width' => 50],
        'stock_expired' => [true => 'Expired','width' => 80],
    ];
}
