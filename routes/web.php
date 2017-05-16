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

Route::get('/', function () {
    return view('welcome');
});

Route::get('get-old-data', 'AircallController@getOldData');

Route::get('reporting', 'CallsController@getReportingDetails');
Route::get('filter-calls/{date}/{location}', 'CallsController@getFilteredCalls');
Route::get('filter-graph/{date}/{location}', 'CallsController@getFilteredCallsGraph');

Route::post('call-archived', 'CallsController@postCallArchived');
