<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Employee Controller

//Route for Login View
Route::get('/', function () {
    return view('login');
})->name('login');

//Route for Language Change
Route::get('language/switch/{locale}', 'LanguageController@switch')->name('language.switch');


Route::prefix('employees')->group(function () {
    //Route for Login Check
    Route::post('/login-check', 'EmployeeController@loginCheck')->name('login.check');
    //Route for logout
    Route::get('/logout', 'EmployeeController@logout')->name('logout');
});

Route::prefix('categories')->group(function () {
    //Route for Register View
    Route::get('/register', 'CategoryController@register')->name('items.register');
    Route::post('/storeCategory', 'CategoryController@storeCategory')->name('categories.store');
    Route::get('/get-categories', 'CategoryController@getCategories')->name('categories.getCategories');
    Route::delete('/destroyCategory/{id}', 'CategoryController@destroyCategory')->name('categories.destroy');
});

Route::prefix('items')->group(function () {
    //Route for ItemController storeItem
    Route::post('/store-item', 'ItemController@storeItem')->name('items.store');

    //Route for ItemController to show all items
    Route::get('/show', 'ItemController@showAllItems')->name('items.show');

    //Route for ItemController to show category dashboard
    Route::get('/category-dashboard', 'ItemController@categoryDashboard')->name('items.category-dashboard');

    //Route for ItemController to show items under each category
    Route::get('/items-category/{id}', 'ItemController@itemsForEachCategory')->name('items.items-category');

    //Route for ItemController to search items
    Route::get('/search', 'ItemController@search')->name('items.search');

    // Route for downloading all items as PDF
    Route::get('/download-pdf', [ItemController::class, 'downloadPDF'])->name('items.download-pdf');

    // Route for downloading all items as Excel
    Route::get('/download-excel', [ItemController::class, 'exportItemsAll'])->name('items.download-excel');

    //Router for ItemController to show detail item
    Route::get('/{id}', 'ItemController@show')->name('items.show-detail');

    //Router for ItemController to fill  auto data in search box
    Route::post('/fetch', 'ItemController@fetch')->name('items.fetch');

    // Active Inactive button
    Route::post('/toggle', 'ItemController@toggle')->name('items.toggle');

    Route::post('/toggle-two', 'ItemController@toggleTwo')->name('items.toggle-two');

    //item delete
    Route::delete('/{item}', 'ItemController@destroy')->name('items.destroy');

    //item edit
    Route::get('/{id}/edit', 'ItemController@edit')->name('items.edit');

    //item update
    Route::put('/{item}', 'ItemController@update')->name('items.update');

    //Autocomplete
    Route::get('/autocomplete/item_id', [ItemController::class, 'autocompleteItemId'])->name('autocomplete.item_id');
    Route::get('/autocomplete/item_code', [ItemController::class, 'autocompleteItemCode'])->name('autocomplete.item_code');
    Route::get('/autocomplete/item_name', [ItemController::class, 'autocompleteItemName'])->name('autocomplete.item_name');
});




//Excel Download Format in Excel Register
Route::get('/export', 'ItemController@export')->name('items.excelFormat');

//Excel Import in Excel Register
Route::post('import-students', 'ItemController@importItems')->name('items.import');
