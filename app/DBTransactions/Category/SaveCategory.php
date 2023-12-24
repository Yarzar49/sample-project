<?php
Namespace App\DBTransactions\Category;
use App\Models\Category;
use App\Classes\DBTransaction;

class SaveCategory extends DBTransaction
{
    /**
     * Save New Category 
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-22
     *
     */
    private $request;

    /**
    * Constructor to assign interface to variable
    */
    public function __construct($request) {
        $this->request = $request;
    }
    
    /**
     * Category save process
     *
     * @author yarzartinshwe
     * @created 2023-6-22
     *
     */
    public function process() {

        $request = $this->request;
        $category = new Category();
        $category->category_name = $request->categoryName;
        $category->save();        

        if(!$category) {
            return ['status' => false, 'error' => 'Employee not saved'];
        }

        return ['status' => true, 'error' => ''];
    }

}
?>