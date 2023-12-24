<?php

namespace App\DBTransactions\ItemsUpload;

use App\Models\Item;
use App\Models\ItemsUpload;
use App\Classes\DBTransaction;

class DeleteItemsUpload extends DBTransaction
{
     /**
     *  DeleteItemsUpload
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
     * ItemsUpload Delete process
     *
     * @author yarzartinshwe
     * @created 2023-7-3
     *
     */
    public function process()
    {
        $id = $this->id;
        $deleteItemsUploadPublic = ItemsUpload::where('item_id', $id)->first();
        
        if ($deleteItemsUploadPublic->file_path) {
            $photoPath = public_path($deleteItemsUploadPublic->file_path);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        $deleteItemsUpload = ItemsUpload::where('item_id', $id)->forceDelete();
        if (!$deleteItemsUpload) {
            return ['status' => false, 'error' => 'Employee not updated'];
        }

        return ['status' => true, 'error' => ''];

    }
    }
}