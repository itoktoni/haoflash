<?php

use Illuminate\Support\Facades\Route;
use Modules\Item\Dao\Facades\ProductFacades;
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

Route::match(
    [
        'GET',
        'POST'
    ],
    'category_api',
    function () {
        $input = request()->get('id');
       
        $query = false;
        if ($input) {
            $query = ProductFacades::where(ProductFacades::mask_category_id(), $input);
            return $query->get()->toArray();
        }
        return $query;
    }
)->name('category_api');