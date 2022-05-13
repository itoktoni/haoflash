<?php

namespace Modules\Procurement\Dao\Models;

class StockBdp extends Stock
{
    public $datatable = [
        'stock_id' => [false => 'Code', 'width' => 50],
        'stock_branch_id' => [false => 'Code', 'width' => 50],
        'supplier_name' => [true => 'Supplier', 'width' => 100],
        'branch_name' => [true => 'Branch', 'width' => 100],
        'product_name' => [true => 'Product'],
        'product_description' => [true => 'Product Description'],
        'stock_qty' => [true => 'Qty', 'width' => 50],
        'stock_buy' => [true => 'Buying Price', 'width' => 50],
        'stock_expired' => [true => 'Expired', 'width' => 80],
    ];
}
