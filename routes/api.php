<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('employee/index-filtering', 'EmployeeController@indexFiltering');
Route::get('employee/index-paging', 'EmployeeController@indexPaging');

Route::post('import-excel-csv-file', [ExcelCSVController::class, 'importExcelCSV']);

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');

    Route::resource('employees', 'EmployeeController'::class);
});

