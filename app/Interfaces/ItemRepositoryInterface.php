<?php

namespace App\Interfaces;

interface ItemRepositoryInterface
{
    /**
     * ItemRepositoryInterface
     * @author yarzartinshwe
     * @created 2023-6-21
     */
    public function getAllItems();

    public function getItemById($id);

    public function getItemByIdForToggle($id);

    public function getItemByIdWithTrashed($id);

    public function getLatestIdValue();

    public function getItemByIdForEditForm($id);

    public function getItemByItemId($itemId);

    public function getSearchItem($request);

    public function getSearchItemForPaginate($itemId, $itemCode, $itemName, $categoryName);

    public function getItemDetail($id);

    public function getAllItemsForPdfDownload($pageNumber);

    public function getSearchItemForDownload($request);

    public function getAllItemsForExcelDownload($pageNumber);    
    public function getIdValueToReomove($category);

    public function getItemToToggle($itemId);

    public function getItemsForEachCategory($id);

    public function getItemIdsForAutoComplete($term, $startValue);
    public function getItemCodeForAutoComplete($term);
    public function getItemNameForAutoComplete($term);

}
