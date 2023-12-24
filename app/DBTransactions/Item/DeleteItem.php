<?php

Namespace App\DBTransactions\Item;

use App\Models\Item;
use App\Classes\DBTransaction;

class DeleteItem extends DBTransaction
{
    /**
     *  DeleteItem
     *
     * @author yarzartinshwe
     *
     * @created 2023-7-3
     *
     */
    private $id;

    /**
    * Constructor to assign interface to variable
    */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Item Delete process
     *
     * @author yarzartinshwe
     * @created 2023-7-3
     *
     */
    public function process()
    {
        $id = $this->id;
        $item = Item::find($id);
        if ($item) {
            $deleteItem = Item::where('id', $id)->forceDelete();
            return ['status' => true, 'error' => ''];
        } else {
            return ['status' => false, 'error' => 'Item not updated'];
        }       

    }
}