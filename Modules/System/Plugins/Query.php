<?php

namespace Modules\System\Plugins;

use Modules\Item\Dao\Enums\CategoryType;
use Modules\Item\Dao\Facades\CategoryFacades;

class Query
{
    public static function getCategoryType($id)
    {
        $category = CategoryFacades::find($id);
        return $category && $category->category_type == CategoryType::Accesories ? true : false;
    }
}
