<?php

use Illuminate\Support\Facades\Route;
use Modules\Item\Dao\Repositories\ProductRepository;

Route::match(
    [
        'GET',
        'POST'
    ],
    'product_api',
    function () {
        $input = request()->get('id');
        $supplier = request()->get('supplier');

        $product = new ProductRepository();
        $query = false;
        if ($input) {
            $query = $product->dataRepository()->where($product->getKeyName(), $input);
            return $query->first()->toArray();
        }
        return $query;
    }
)->name('product_api');

