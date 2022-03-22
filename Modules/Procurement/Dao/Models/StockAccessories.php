<?php

namespace Modules\Procurement\Dao\Models;

class StockAccessories extends Stock
{
    public $datatable = [
        'stock_id' => [false => 'Code', 'width' => 50],
        'supplier_name' => [true => 'Supplier', 'width' => 100],
        'branch_name' => [true => 'Branch', 'width' => 100],
        'product_sku' => [true => 'Product SKU', 'width' => 120],
        'product_name' => [true => 'Product'],
        'product_description' => [true => 'Description'],
        'stock_qty' => [true => 'Qty', 'width' => 50],
        'stock_buy' => [true => 'Price', 'width' => 50],
        'stock_expired' => [false => 'Expired', 'width' => 80],
    ];
}
