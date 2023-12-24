<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * CategoryRepository
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-23
     *
     */


    /**
     *Get all categories
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function getAllCategories()
    {
        $categories = Category::all();
        return $categories;
    }

    /**
     *Get category with given id
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     * @param $id
     *
     */
    public function getCategoryById($id)
    {

        return Category::findOrFail($id);
    }

    /**
     *Get Selected Category IdValue
     *
     * @author yarzartinshwe
     * @created 2023-7-5
     * @param $request
     *
     */
    public function getSelectedCategoryIdValue($request)
    {
        $categoryIdValue = DB::table('categories')->where('category_name', $request->categoryName)->pluck('id')->first();
        return $categoryIdValue;
    }

     /**
     *Get items count of each category
     *
     * @author yarzartinshwe
     * @created 2023-7-10
     * 
     */
    public function getCategoryNameAndItemCount()
    {
        $results = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select('categories.category_name', 'categories.id', DB::raw('COUNT(items.id) as item_count'))
            ->groupBy('categories.category_name')
            ->get();
        
        return $results;
    }
}
