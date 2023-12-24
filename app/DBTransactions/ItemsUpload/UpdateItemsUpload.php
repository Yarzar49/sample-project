<?php

namespace App\DBTransactions\ItemsUpload;

use App\Models\Item;
use App\Models\ItemsUpload;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateItemsUpload extends DBTransaction
{
    /**
     *  Update image infos in ItemsUpload table
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
     * ItemsUpload Update process
     *
     * @author yarzartinshwe
     * @created 2023-7-3
     *
     */
    public function process()
    {
        $request = $this->request;
        $id = $this->id;

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            if ($file->isValid()) {
                $fileName = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();

                // Get the file path
                $filePath = 'images/' . $fileName;

                // Get the file size 
                $fileSize = $file->getSize();

                $exists = (ItemsUpload::where('item_id', $id)->count() > 0);
                Log::info($exists);

                if ($exists) {

                    //Move the uploaded file
                    $itemUpload = ItemsUpload::where('item_id', $id)->first();
                    $itemUploadId = $itemUpload->item_id;
                    $fileNamePublic = $itemUploadId . "_" . $fileName;
                    $filePath = 'images/' . $fileNamePublic;

                    $deleteItemsUploadPublic = ItemsUpload::where('item_id', $id)->first();

                    if ($deleteItemsUploadPublic->file_path) {
                        $photoPath = public_path($deleteItemsUploadPublic->file_path);
                        if (file_exists($photoPath)) {
                            unlink($photoPath);
                        }
                    }
                    $updateItemsUpload = ItemsUpload::where('item_id', $id)->update(
                        [
                            'item_id' => $id,
                            'file_path' => $filePath,
                            'file_type' => $fileExtension,
                            'file_size' => $fileSize,
                        ]
                    );
                    $file->move(public_path('images'), $fileNamePublic);
                } else {

                    $fileNamePublic = $id . "_" . $fileName;
                    $filePath = 'images/' . $fileNamePublic;
                    $file->move(public_path('images'), $fileNamePublic);

                    $updateItemsUpload = new ItemsUpload();
                    $updateItemsUpload->item_id = $id; //
                    $updateItemsUpload->file_path = $filePath;
                    $updateItemsUpload->file_type = $fileExtension;
                    $updateItemsUpload->file_size = $fileSize; //
                    $updateItemsUpload->save();
                }
            }
        } elseif ($request->remove_image) {
            // $photoId = $request->photo_id;
            $deleteItemsUploadPublic = ItemsUpload::where('item_id', $id)->first();

            if ($deleteItemsUploadPublic->file_path) {
                $photoPath = public_path($deleteItemsUploadPublic->file_path);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
            $updateItemsUpload = ItemsUpload::where('item_id', $id)->delete();
        }

        if (!$updateItemsUpload) {
            return ['status' => false, 'error' => 'Item not updated'];
        }

        return ['status' => true, 'error' => ''];
    }
}
