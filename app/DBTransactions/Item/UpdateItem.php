<?php
Namespace App\DBTransactions\Item;

use App\Models\Item;
use App\Classes\DBTransaction;

class UpdateItem extends DBTransaction
{
    /**
     * UpdateItem 
     *
     * @author yarzartinshwe
     *
     * @created 2023-7-3
     *
     */
    private $request, $id;

    /**
    * Constructor to assign interface to variable
    */
    public function __construct($request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    /**
     * Item Update process
     *
     * @author yarzartinshwe
     * @created 2023-7-3
     *
     */
    public function process()
    {
        $request = $this->request;
        $id = $this->id;

        $item = Item::find($id);
        
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
            return ['status' => false, 'error' => 'Item not updated'];
        }

        return ['status' => true, 'error' => ''];
    }
}