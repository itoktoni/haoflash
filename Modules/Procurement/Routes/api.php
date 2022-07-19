<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\System\Plugins\Helper;

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
    'product_delivery_api',
    function () {
        $input = request()->get('id');

        $query = false;
        if ($input) {
            $query = DB::table('view_summary_stock')->where('id', $input)->where('stock_branch_id', env('BRANCH_ID'))->first();
            return $query;
        }
        return $query;
    }
)->name('product_delivery_api');

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

Route::match(
    [
        'GET',
        'POST'
    ],
    'category_delivery_api',
    function () {
        $input = request()->get('id');
        $query = false;
        if ($input) {
            $query = DB::table('view_summary_stock')->where(StockFacades::mask_branch_id(), env('BRANCH_ID'))->where(ProductFacades::mask_category_id(), $input);
            if ($data = $query->get()) {
                $check = $data->map(function ($item) {

                    $price = Helper::createRupiah($item->stock_buy);
                    $exp = $item->stock_expired ? " - exp [ $item->stock_expired ]" : '';
                    $data['id'] = $item->id;
                    $data['name'] = "$item->product_name : Supplier : ($item->supplier_name) - Stock : ($item->stock_qty) = $price $exp";
                    return $data;
                });
                return $check;
            }
            return $data;
        }
        return $query;
    }
)->name('category_delivery_api');

Route::match(
    [
        'GET',
        'POST'
    ],
    'city_api',
    function () {
        $input = request()->get('id');

        $query = false;
        if ($input) {
            $query = DB::table('rajaongkir_cities')->where('rajaongkir_city_province_id', $input);
            return $query->get()->toArray();
        }
        return $query;
    }
)->name('city_api');

Route::match(
    [
        'GET',
        'POST'
    ],
    'area_api',
    function () {
        $input = request()->get('id');

        $query = false;
        if ($input) {
            $query = DB::table('rajaongkir_areas')->where('rajaongkir_area_city_id', $input);
            return $query->get()->toArray();
        }
        return $query;
    }
)->name('area_api');
