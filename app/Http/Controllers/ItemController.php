<?php

namespace App\Http\Controllers;

use SplFileInfo;
use App\Models\Item;
use Mpdf\Mpdf as PDF;
use App\Models\Category;
use App\Imports\ItemImport;
use App\Models\ItemsUpload;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Mpdf\Output\Destination;
use App\Imports\ItemImportFirst;
use Illuminate\Http\UploadedFile;
use App\Exports\ExcelRegistration;
use App\Http\Requests\EditRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\DBTransactions\Item\SaveItem;
use App\Http\Requests\RegisterRequest;
use App\DBTransactions\Item\DeleteItem;
use App\DBTransactions\Item\UpdateItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\ItemRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ItemsUploadRepositoryInterface;
use App\DBTransactions\ItemsUpload\SaveItemsUpload;
use App\DBTransactions\ItemsUpload\DeleteItemsUpload;
use App\DBTransactions\ItemsUpload\UpdateItemsUpload;

class ItemController extends Controller
{
    /**
     * Items CRUD methods
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-22
     *
     */
    protected $itemInterface;
    protected $categoryInterface;

    protected $itemsUploadInterface;

    /**
     * Constructor to assign interface to variables
     */
    public function __construct(ItemsUploadRepositoryInterface $itemsUploadInterface, ItemRepositoryInterface $itemInterface, CategoryRepositoryInterface $categoryInterface)
    {
        $this->itemInterface = $itemInterface;
        $this->categoryInterface = $categoryInterface;
        $this->itemsUploadInterface = $itemsUploadInterface;
    }

    /**
     * Store Item in the database
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-6-22
     *
     */
    public function storeItem(RegisterRequest $request)
    {
        $saveItem = new SaveItem($request);
        $save = $saveItem->executeProcess();
        

        $saveItemsUpload = new SaveItemsUpload($request);
        $save = $saveItemsUpload->executeProcess();
        session(['search' => false]);
        return redirect()->route('items.show')->with("success", "Item registered successfully");
    }

    /**
     * Show Items List view
     *
     * @author yarzartinshwe
     * @created 2023-6-26
     *
     */
    public function showAllItems(Request $request)
    {
        $currentPage = $request->input('page', 1);
        session(['currentPage' => $currentPage]);

        $itemId = session('itemId');
        $itemCode = session('itemCode');
        $itemName = session('itemName');
        $categoryName = session('categoryName');      
        
        if (session('search') == true) {                      
            $items = $this->itemInterface->getSearchItemForPaginate($itemId, $itemCode, $itemName, $categoryName);
            if ($items->count() > 0) {
                $categories = $this->categoryInterface->getAllCategories();
                return view('item-list', compact('items', 'categories'));                
            }            
        }

        $items = $this->itemInterface->getAllItems();
        $categories = $this->categoryInterface->getAllCategories();
        
        return view('item-list', compact('items', 'categories'));
    }

    /**
     * Show item details
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-6-26
     *
     */
    public function show($id)
    {
        $item = $this->itemInterface->getItemDetail($id);
        if ($item) {
            return view('detail-item', compact('item'));
        } else {
            session()->flash('error', 'Item is not found.');
            return redirect()->back()->with('error', 'Item is not found.');
        }
    }

    /**
     * Excel Export
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function export()
    {
        return Excel::download(new ExcelRegistration, 'Item List.xlsx');
    }

    /**
     * Excel Import
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-6-23
     *
     */
    public function importItems(Request $request)
    {
        // check if the excel file is present in the request or file type is not
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'mimes:xls,xlsx'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('items.register')->withErrors($validator)->withInput();
        }

        $file = $request->file('file');

        try {
            Excel::import(new ItemImportFirst(), $file);

            return redirect()->route('items.show')->with(['success' => 'Items registered successfully']);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('items.register')->with(['message' => $e->getMessage()]);
        }
    }

    /**
     *Search items in search form
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-6-23
     *
     */
    public function search(Request $request)
    {
        $currentPage = $request->input('page', 1);
        session(['currentPage' => $currentPage]);        

        $itemId = $request->input('item_id');
        $itemCode = $request->input('item_code');
        $itemName = $request->input('item_name');
        $categoryName = $request->input('category_name');

        session(['itemId' => $itemId, 'itemCode' => $itemCode, 'itemName' => $itemName, 'categoryName' => $categoryName]);
        
        if (session()->has('itemId') || session()->has('itemCode') || session()->has('itemName') || session()->has('categoryName')) {
            session(['search' => true]);            
        }

        $items = $this->itemInterface->getSearchItem($request);
        $categories = $this->categoryInterface->getAllCategories();
        return view('item-list', compact('items', 'categories'));
        

    }

    /**
     *Search items in search form
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-6-23
     *
     */
    public function fetch(Request $request)
    {
        $itemId = $request->item_id;
        $item = $this->itemInterface->getItemByItemId($itemId);

        if ($item) {
            return response()->json([
                'success' => true,
                'data' => [
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                ],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => null,
            ]);
        }
    }

    /**
     *Download PDF
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-6-23
     *
     */
    public function downloadPDF(Request $request)
    {
        $page = $request->input('page');
        if ($request->input('item_id') || $request->input('item_code') || $request->input('item_name') || $request->input('category_name')) {
            $items = $this->itemInterface->getSearchItemForDownload($request);
        } else {
            $items = $this->itemInterface->getAllItemsForPdfDownload($page);
        }

        // Setup a filename 
        $documentFileName = "items.pdf";
        // Create the mPDF document
        $pdf = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);

        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
        ];

        $html = View::make('download.pdf')->with('items', $items);
        $html->render();

        // Use Blade if you want
        $pdf->WriteHTML($html);
        $pdf->Output($documentFileName, Destination::DOWNLOAD);

        // Save PDF on public storage 
        Storage::disk('public')->put($documentFileName, $pdf->Output($documentFileName, "S"));
    }

    /**
     *Download Excel
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-6-23
     *
     */
    public function exportItemsAll(Request $request)
    {
        $page = $request->input('page');
        if ($request->input('item_id') || $request->input('item_code') || $request->input('item_name') || $request->input('category_name')) {
            $items = $this->itemInterface->getSearchItemForDownload($request);
        } else {
            $items = $this->itemInterface->getAllItemsForExcelDownload($page);
        }

        return Excel::download(new ItemsExport($items), 'items.xlsx');
    }

    /**
     *Update an item
     *
     * @author yarzartinshwe
     * @param $request, $id
     * @created 2023-7-3
     *
     */
    public function update(EditRequest $request, $id)
    {   Log::info($id); 
        $item = $this->itemInterface->getItemByIdWithTrashed($id);
        $currentPage = session('currentPage', 1);
        if ($item) {
            if ($item->deleted_at == null) {
                $updateItem = new UpdateItem($request, $id);
                $updateItem = $updateItem->executeProcess();

                $updateItemsUpload = new UpdateItemsUpload($request, $id);
                $updateItemsUpload = $updateItemsUpload->executeProcess();
                
                return redirect()->route('items.show',  ['page' => $currentPage])->with('success', 'Item update successfully.');
            } else {                
                return redirect()->route('items.show', ['page' => $currentPage])->with('error', 'Item cannot be updated because it is already inactivated.');
            }
        } else {
            return redirect()->route('items.show', ['page' => $currentPage])->with('error', 'Item is not found.');
        }
    }

    /**
     *Inactive a row in items list table view
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-7-3
     *
     */
    public function toggle(Request $request)
    {
        $itemId = $request->itemId;
        $item = $this->itemInterface->getItemByIdWithTrashed($itemId);

        if ($item) {
            if ($item->deleted_at == null) {
                $item->delete(); // Soft delete the item
                session()->flash('success', 'Item inactive successfully.');
                return response()->json(['success' => 'Item inactive successfully.']);
            } else {
                session()->flash('error', 'Item is already inactive.');
                return response()->json(['error' => 'Item is already inactive.']);
            }
        } else {
            session()->flash('error', 'Item is not found.');
            return response()->json(['error' => 'Item is not found.']);
        }
    }

    /**
     *Active a row in items list table view
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-7-7
     *
     */
    public function toggleTwo(Request $request)
    {
        $itemId = $request->itemId;
        $item = $this->itemInterface->getItemByIdWithTrashed($itemId);
        if ($item) {
            if ($item->deleted_at != null) {
                $item->restore(); // restore the item
                session()->flash('success', 'Item active successfully.');
                return response()->json(['success' => 'Item active successfully.']);
            } else {
                session()->flash('error', 'Item is already active.');
                return response()->json(['error' => 'Item is already active.']);
            }
        } else {
            session()->flash('error', 'Item is not found.');
            return response()->json(['error' => 'Item is  not found.']);
        }
    }


    /**
     *Hard delete the item
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-7-3
     *
     */
    public function destroy($id)
    {
        $item = $this->itemInterface->getItemByIdWithTrashed($id);
        $currentPage = session('currentPage', 1);
        if ($item) {
            if ($item->deleted_at == null) {


                $deleteItem = new DeleteItem($id);
                $deleteItem = $deleteItem->executeProcess();

                $deleteItemsUpload = new DeleteItemsUpload($id);
                $deleteItemsUpload = $deleteItemsUpload->executeProcess();
                session()->flash('success', 'Item deleted successfully.');
                return response()->json(['success' => 'Item deleted successfully.']);
            } else {
                session()->flash('error', 'This item cannot be deleted because it is inactive.');
                return response()->json(['error' => 'This item cannot be deleted because it is inactive.']);
            }
        } else {
            session()->flash('error', 'This item has already been deleted.');
            return response()->json(['error' => 'This item has already been deleted.']);
        }
    }


    /**
     *Edit button in items list table view
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-7-3
     *
     */
    public function edit($id)
    {
        // Retrieve the item from the database
        $item = $this->itemInterface->getItemByIdWithTrashed($id);

        $currentPage = session('currentPage', 1);

        // Retrieve the existing photo path
        $existingPhotoPath = null;
        $itemsUpload = $items = $this->itemsUploadInterface->getItemUploadToEdit($id);
        if ($itemsUpload) {
            $existingPhotoPath = $itemsUpload->file_path;
        }
        $categories = $this->categoryInterface->getAllCategories();
        $item = $this->itemInterface->getItemByIdForEditForm($id);
        if ($item) {
            if ($item->deleted_at == null) {
                return view('edit', compact('item', 'categories', 'existingPhotoPath'));
            } else {
                return redirect()->route('items.show', ['page' => $currentPage])->with('error', 'Item cannot be edited because it is already inactivated');
            }
        } else {
            return redirect()->route('items.show', ['page' => $currentPage])->with('error', 'Item is not found.');
        }
    }

    /**
     *Catogory dashboard to show items count of each category
     *
     * @author yarzartinshwe
     * @created 2023-7-10
     *
     */
    public function categoryDashboard()
    {
        $results = $category = $this->categoryInterface->getCategoryNameAndItemCount();
        return view('dashboards.category-dashboard',  compact('results'));
    }

    /**
     *Items List of each category
     *
     * @author yarzartinshwe
     * @param $id
     * @created 2023-7-10
     *
     */
    public function itemsForEachCategory($id)
    {
        $category = $this->categoryInterface->getCategoryById($id);
        $categoryName = $category->category_name;
        $items = $this->itemInterface->getItemsForEachCategory($id);

        return view('dashboards.items-category', compact('items', 'categoryName'));
    }

    /**
     *Autocomplete for item_id
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-7-10
     *
     */
    public function autocompleteItemId(Request $request)
    {
        $term = intval($request->input('term'));
        $startValue = intval($term);

        // Query the database to fetch the autocomplete data
        $itemsId = $this->itemInterface->getItemIdsForAutoComplete($term, $startValue);

        return response()->json($itemsId);
    }

    /**
     *Autocomplete for item_code
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-7-10
     *
     */
    public function autocompleteItemCode(Request $request)
    {
        $term = $request->input('term');

        // Query the database or perform any other logic to retrieve matching item codes
        $itemCodes = $this->itemInterface->getItemCodeForAutoComplete($term);
        return response()->json($itemCodes);
    }

    /**
     *Autocomplete for item_name
     *
     * @author yarzartinshwe
     * @param $request
     * @created 2023-7-10
     *
     */
    public function autocompleteItemName(Request $request)
    {
        $term = $request->input('term');

        // Query the database or perform any other logic to retrieve matching item names
        $itemNames = $this->itemInterface->getItemNameForAutoComplete($term);
        return response()->json($itemNames);
    }
}
