<?php

use Illuminate\Http\Request;

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

Route::middleware('api')->get('/', function () {
    return array(
        'status' => 'OK',
        'path' => '/'
    );
});

Route::resource('lists', 'ListsController');
Route::resource('lists/{listid}/members', 'MembersController');
