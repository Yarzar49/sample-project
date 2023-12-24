<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use App\Interfaces\ItemRepositoryInterface;
use App\DBTransactions\Category\SaveCategory;
use App\DBTransactions\Category\DeleteCategory;
use App\Interfaces\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    /**
     * New category store and remove category
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-22
     *
     */

    protected $itemRepositoryInterface;
    protected $categoryRepositoryInterface;

    /**
     * Constructor to assign interface to variable
     */
    public function __construct(ItemRepositoryInterface $itemRepositoryInterface, CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->itemRepositoryInterface = $itemRepositoryInterface;
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    /**
     * Register form
     *
     * @author yarzartinshwe
     * @created 2023-6-22
     *
     */
    public function register()
    {

        $categories = $this->categoryRepositoryInterface->getAllCategories();

        $idCode = $this->itemRepositoryInterface->getLatestIdValue();

        if (!$idCode) {
            $idCode = 0;
        }

        $itemId = $idCode + 10001;

        return view('register', compact('categories', 'itemId'));
    }

    /**
     * Stor a new category
     *
     * @author yarzartinshwe
     * @created 2023-6-22
     * @param $request
     * @return $categoryIdValue
     *
     */
    public function storeCategory(Request $request)
    {
        //Method from Ajax
        $saveCategory = new SaveCategory($request);
        $save = $saveCategory->executeProcess();
        $categoryIdValue = $this->categoryRepositoryInterface->getSelectedCategoryIdValue($request);
        Log::info($categoryIdValue);
        return $categoryIdValue;
    }

    /**
     * Get category selected box to render from new blade 
     *
     * @author yarzartinshwe
     * @created 2023-6-22
     * 
     */
    public function getCategories()
    {
        //Method from Ajax
        $categories = $this->categoryRepositoryInterface->getAllCategories();
        $options = View::make('partials.category_options', ['categories' => $categories])->render();
        return $options;
    }

    /**
     * Delete category 
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-6-22
     *
     */
    public function destroyCategory($id)
    {
        //Method from Ajax
        $category = $this->categoryRepositoryInterface->getCategoryById($id);
        if ($category) {
            $categoryIdValue =  $this->itemRepositoryInterface->getIdValueToReomove($category);
            if ($categoryIdValue == null) {
                $delete = new DeleteCategory($id);
                $delete = $delete->executeProcess();
            } else {
                return redirect();
            }
        }
    }
}
