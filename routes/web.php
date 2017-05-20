<?php

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

Route::get('get-old-data', 'AircallController@getOldData');

Route::get('/', 'CallsController@getReportingDetails');
Route::get('filter-reports/{date}/{number}', 'CallsController@getFilteredReports');
Route::get('calls', 'CallsController@getCallsDetails');
Route::get('import', 'AircallController@getImportPage');
Route::get('get-logs/{page}', 'AircallController@getLogs');
