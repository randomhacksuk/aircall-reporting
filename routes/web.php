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

Route::post('aircall-users', 'UsersController@postAircallUsers');
Route::post('aircall-calls', 'CallsController@postAircallCalls');

Route::get('reporting', 'CallsController@getReportingDetails');
Route::get('filter-calls/{date}/{location}', 'CallsController@getFilteredCalls');
Route::get('filter-graph/{date}/{location}', 'CallsController@getFilteredCallsGraph');
