<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Modules\Item\Dao\Facades\CategoryFacades;

Route::match(
    [
        'GET',
        'POST'
    ],
    'get_category_api',
    function () {
        $input = request()->get('id');
       
        $query = false;
        if ($input) {
            $query = CategoryFacades::find($input);
            return $query->toArray();
        }
        return $query;
    }
)->name('get_category_api');