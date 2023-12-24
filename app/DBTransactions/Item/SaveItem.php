<?php
Namespace App\DBTransactions\Item;
use App\Models\Item;
use App\Models\ItemsUpload;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\DB;



class SaveItem extends DBTransaction
{

    /**
     * Save New Item 
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
     * Item save process
     *
     * @author yarzartinshwe
     * @created 2023-6-22
     *
     */
    public function process() {

        $request = $this->request;
        $item = new Item();
        $selectedCategoryId = $request->input('categoryName');

        $item->item_id = $request->item_id;
        $item->item_code = $request->item_code;
        $item->item_name = $request->item_name;
        $item->category_id = $selectedCategoryId;
        $item->safety_stock = $request->safety_stock;
        $item->received_date = $request->received_date;
        $item->description = $request->description;
        $item->save();

        if(!$item) {
            return ['status' => false, 'error' => 'Employee not saved'];
        }

        return ['status' => true, 'error' => ''];
    }

}
?>