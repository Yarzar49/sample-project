<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\ItemsUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ItemRepositoryInterface;



class ItemRepository implements ItemRepositoryInterface
{
    /**
     * ItemRepository
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-23
     *
     */

    /**
     *Get all items
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function getAllItems()
    {
        $items = DB::table('items')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->select('items.*', 'categories.category_name')
            ->orderBy('items.id', 'desc')
            ->paginate(20);

        return $items;
    }

    /**
     *Get all items for detail download
     *
     * @author yarzartinshwe
     * @created 2023-6-30
     *
     */
    public function getAllItemsForPdfDownload($pageNumber)
    {
        $items = DB::table('items')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->select('items.*', 'categories.category_name')
            ->orderBy('items.id', 'desc')
            ->get();
        return $items;
    }

    /**
     *Get item with given id for toggle inactive
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-7-7
     *
     */
    public function getItemByIdForToggle($id)
    {
        $item = Item::find($id);
        return $item;
    }



    /**
     *Get item with given id
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-6-23
     *
     */
    public function getItemById($id)
    {

        return Item::findOrFail($id);
    }

    /**
     *Get item with Trashed item
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-7-7
     *
     */
    public function getItemByIdWithTrashed($id)
    {
        $item = Item::withTrashed()->find($id);
        return $item;
    }

    /**
     *Get Latest Id Value
     *
     * @author yarzartinshwe
     * @created 2023-7-5
     *
     */
    public function getLatestIdValue()
    {
        $idCode = Item::withTrashed()->latest('id')->value('id');
        return $idCode;
    }

    /**
     *Get an item to update
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-7-3
     *
     */
    public function getItemByIdForEditForm($id)
    {
        $item = DB::table('items')
            ->leftJoin('items_uploads', 'items_uploads.item_id', '=', 'items.id')
            ->select('items.*', 'items_uploads.file_path')
            ->where('items.id', $id)
            ->first();
        return $item;
    }

    /**
     *Get item by item_id
     *
     * @author yarzartinshwe
     * @param $itemId
     * @created 2023-6-30
     *
     */
    public function getItemByItemId($itemId)
    {
        return Item::where('item_id', '=', $itemId)->first();
    }

    /**
     *Get items according to search results
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-6-23
     */
    public function getSearchItem($request)
    {

        $query = DB::table('items')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->select('items.*', 'categories.category_name')
            ->orderBy('items.id', 'desc');

        if ($request->filled('item_id')) {
            $query->where('items.item_id', 'LIKE', '%' . $request->input('item_id').'%');
        }

        if ($request->filled('item_code')) {
            $query->where('items.item_code', 'LIKE', '%' . $request->input('item_code').'%');
        }

        if ($request->filled('item_name')) {
            $query->where('items.item_name', 'LIKE', '%' . $request->input('item_name').'%');
        }

        if ($request->filled('category_name')) {
            $query->where('categories.category_name', 'LIKE', '%' . $request->input('category_name').'%');
        }

        $items = $query->paginate(20);
        return $items;
    }

     /**
     *Get items according to search results
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-6-23
     */
    public function getSearchItemForPaginate($itemId, $itemCode, $itemName, $categoryName)
    {

        $query = DB::table('items')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->select('items.*', 'categories.category_name')
            ->orderBy('items.id', 'desc');

        if ($itemId) {
            $query->where('items.item_id', 'LIKE', '%' . $itemId)->get();
        }

        if ($itemCode) {
            $query->where('items.item_code', 'LIKE', '%' . $itemCode)->get();
        }

        if ($itemName) {
            $query->where('items.item_name', 'LIKE', '%' . $itemName)->get();
        }

        if ($categoryName) {
            $query->where('categories.category_name', 'LIKE', '%' . $categoryName)->get();
        }

        $items = $query->paginate(20);
        return $items;
    }

    /**
     *Get search items by item_id
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-6-30
     *
     */
    public function getSearchItemForDownload($request)
    {
        $query = DB::table('items')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->select('items.item_id', 'items.item_code', 'items.item_name', 'categories.category_name', 'items.safety_stock', 'items.received_date', 'items.description')
            ->orderBy('items.id', 'desc');

        if ($request->filled('item_id')) {
            $query->where('items.item_id', 'LIKE', '%' . $request->input('item_id'));
        }

        if ($request->filled('item_code')) {
            $query->where('items.item_code', 'LIKE', '%' . $request->input('item_code'));
        }

        if ($request->filled('item_name')) {
            $query->where('items.item_name', 'LIKE', '%' . $request->input('item_name'));
        }

        if ($request->filled('category_name')) {
            $query->where('categories.category_name', 'LIKE', '%' . $request->input('category_name'));
        }

        $items = $query->get();
        return $items;
    }

    /**
     *Get detail of item with given id
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-6-23
     *
     */
    public function getItemDetail($id)
    {
        $itemIdValue = ItemsUpload::where('item_id', '=', "$id")
            ->select('item_id')
            ->first();
        Log::info($itemIdValue);
        if ($itemIdValue) {
            $item = Item::join('categories', 'categories.id', 'items.category_id')
                ->join('items_uploads', 'items_uploads.item_id', 'items.id')
                ->withTrashed()
                ->select('items.*', 'categories.category_name', 'items_uploads.file_path')
                ->find($id);
        } else {
            // $defaultImage = 'images/no_image.png';

            $item = Item::join('categories', 'categories.id', 'items.category_id')
                ->withTrashed()
                ->select('items.*', 'categories.category_name')
                ->find($id);
        }
        return $item;
    }

    /**
     *Get all items for excel download
     *
     * @author yarzartinshwe
     * @created 2023-6-30
     *
     */
    public function getAllItemsForExcelDownload($pageNumber)
    {
        $items = DB::table('items')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->select('items.item_id', 'items.item_code', 'items.item_name', 'categories.category_name', 'items.safety_stock', 'items.received_date', 'items.description')
            ->orderBy('items.id', 'desc')
            ->get();
        return $items;
    }

    /**
     *Get Id value to reomove
     *
     * @author yarzartinshwe
     * @param $category
     * @created 2023-6-30
     *
     */
    public function getIdValueToReomove($category)
    {
        $categoryIdValue = DB::table('items')->where('category_id',  $category->id)->pluck('category_id')->first();
        return $categoryIdValue;
    }


    /**
     *Get Item To Toggle
     *
     * @author yarzartinshwe
     * @param $itemId
     * @created 2023-6-30
     *
     */
    public function getItemToToggle($itemId)
    {
        $item = Item::withTrashed()->findOrFail($itemId);
        return $item;
    }

    /**
     *Get items of each category
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-7-10
     * 
     */
    public function getItemsForEachCategory($id)
    {
        $items = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.*', 'categories.category_name')
            ->where('categories.id', $id)
            ->orderBy('items.id', 'desc')
            ->paginate(20);
        return $items;
    }

     /**
     *Get item_id for autocomplete
     *
     * @author yarzartinshwe
     * @param $term, $startValue
     * @created 2023-7-10
     *
     */
    public function getItemIdsForAutoComplete($term, $startValue)
    {
        // Query the database to fetch the autocomplete data
        $itemIds = Item::withTrashed()
            ->where('item_id', 'like', $startValue . '%')
            ->pluck('item_id')
            ->map(function ($itemId) {
                return strval($itemId);
            });

        return $itemIds;
    }

     /**
     *Get item_codes for autocomplete
     *
     * @author yarzartinshwe
     * @param $term
     * @created 2023-7-10
     */
    public function getItemCodeForAutoComplete($term)
    {
        $itemCodes = Item::withTrashed()
            ->where('item_code', 'like', $term . '%')
            ->groupBy('item_code')
            ->pluck('item_code');
        return $itemCodes;
    }

     /**
     *Get item_name for autocomplete
     *
     * @author yarzartinshwe
     * @param $term
     * @created 2023-7-10
     */
    public function getItemNameForAutoComplete($term)
    {
        $itemNames = Item::withTrashed()
            ->where('item_name', 'like', $term . '%')
            ->groupBy('item_name')
            ->pluck('item_name');
        return $itemNames;
    }
}
