<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;

class CategoriesService
{

    public function backCategoryId($category) {
        $category_filter = str_replace('-', ' ', $category);
        $category_id = DB::table("categories")
            ->where("name", "=", $category_filter)
            ->value("id");
        return $category_id;
    }

}
