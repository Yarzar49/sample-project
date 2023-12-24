<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    /**
     * CategoryRepositoryInterface
     * @author yarzartinshwe
     * @created 2023-6-21
     */
    public function getAllCategories();
    public function getCategoryById($id);
    public function getSelectedCategoryIdValue($request);
    public function getCategoryNameAndItemCount();
}
