<?php

namespace App\Imports;
use App\Models\Item;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Interfaces\ItemRepositoryInterface;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Interfaces\CategoryRepositoryInterface;


class ItemImport implements ToCollection
{
    /*
     * Excel item data import
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-23
     *
     */

    public function collection(Collection $rows)
    {
        
        $dataRows = $rows->slice(2); // cutting out the 2 rows         
        
        if ($dataRows->isEmpty()) {
            throw new \Exception("No records found!");
        }

        if ($dataRows->count() > 100) {
            throw new \Exception("The maximum limit of 100 rows has been reached!");
        }

        foreach ($dataRows as $index => $row) {
            $rowArray = $row->toArray(); // Convert object to array

            $customMessages = [
                '0.required' => 'Item Code is required!.',
                '1.required' => 'Item Name is required!.',
                '1.alpha_num' => 'Item Name is only characters and integers!.',
                '2.required' => 'Category Name is required!.',
                '2.exists' => 'The selected Category Name is invalid!.',
                '3.required' => 'Safety Stock is required!.',
                '3.integer' => 'Safety Stock is only interger!.',
                '4.required' => 'Received Date is required!.',
            ];

            $validator = Validator::make($rowArray, [
                '0' => 'required',
                '1' => 'required',
                '2' => 'required|exists:categories,category_name',
                '3' => 'required|integer',
                '4' => 'required',
            ],
            $customMessages);
        
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                Log::info($errors);
                
                $rowNumber = $index + 1; // Adjust row number to account for header row
                $errorMessage = 'Row ' . $rowNumber . ': ' . implode(', ', $errors);
                throw new \Exception($errorMessage);
            }
        
            $categoryIdValue = DB::table('categories')->where('category_name', $row[2])->pluck('id')->first();
            $id = Item::latest('id')->value('id');
            $itemId = $id + 10001;
        
            $newItem = new Item();
            $newItem->item_id = $itemId;
            $newItem->item_code = $row[0];
            $newItem->item_name = $row[1];
            $newItem->category_id = $categoryIdValue;
            $newItem->safety_stock = $row[3];
            $newItem->received_date = Date::excelToDateTimeObject($row[4])->format('Y-m-d');
            $newItem->description = $row[5];
        
            $newItem->save();
        }
    }
}