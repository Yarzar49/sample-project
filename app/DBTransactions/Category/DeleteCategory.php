<?php
Namespace App\DBTransactions\Category;

use App\Models\Category;
use App\Classes\DBTransaction;

class DeleteCategory extends DBTransaction{

     /**
     * DeleteCategory 
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-22
     *
     */

    private $id;

    /**
    * Constructor to assign interface to variable
    */
    public function __construct($id) {
        
        $this->id = $id;

    }
    
    /**
     * Category delete process
     *
     * @author yarzartinshwe
     * @created 2023-6-22
     *
     */
    public function process() {

        
        $id = $this->id;
        $deleteCategory = Category::where('id', $id)->delete();


        if(!$deleteCategory) {
            return ['status' => false, 'error' => 'Employee not deleted'];
        }

        return ['status' => true, 'error' => ''];
}
}
?>