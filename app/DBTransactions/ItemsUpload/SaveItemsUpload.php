<?php

namespace App\DBTransactions\ItemsUpload;

use App\Models\Item;
use App\Models\ItemsUpload;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaveItemsUpload extends DBTransaction
{
    /**
     * Save new image infos in ItemsUpload table
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
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * ItemsUpload save process
     *
     * @author yarzartinshwe
     * @created 2023-6-22
     *
     */
    public function process()
    {

        $request = $this->request;

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            if ($file->isValid()) {
                $fileName = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();

                // Get the file size 
                $fileSize = $file->getSize();

                // Move the uploaded file
                $id = Item::latest('id')->first()->id;
                Log::info($id);
                $fileNamePublic = $id . "_" . $fileName;
                $file->move(public_path('images'), $fileNamePublic);

                // Get the file path
                $filePath = 'images/' . $fileNamePublic;

                $itemUpload = new ItemsUpload();
                $lastItem = Item::latest('id')->first();
                $lastItemId = $lastItem->id;

                $itemUpload->item_id = $lastItemId; //
                $itemUpload->file_path = $filePath;
                $itemUpload->file_type = $fileExtension;
                $itemUpload->file_size = $fileSize; //
                $itemUpload->save();
            }
        }


        if (!$itemUpload) {
            return ['status' => false, 'error' => 'Employee not saved'];
        }

        return ['status' => true, 'error' => ''];
    }
}
