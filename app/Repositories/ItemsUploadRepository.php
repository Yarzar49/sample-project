<?php

namespace App\Repositories;

use App\Models\ItemsUpload;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ItemsUploadRepositoryInterface;

class ItemsUploadRepository implements ItemsUploadRepositoryInterface
{
    /**
     * ItemsUploadRepository
     *
     * @author yarzartinshwe
     *
     * @created 2023-7-5
     *
     */

     /**
     *Get ItemUpload To Edit
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-7-5
     *
     */
    public function getItemUploadToEdit($id)
    {
        $itemsUpload = ItemsUpload::where('item_id', $id)->first();
        return $itemsUpload;
    }

    
}
