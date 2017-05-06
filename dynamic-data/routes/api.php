<?php

use Illuminate\Http\Request;
use App\Http\Controllers\SchemaController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/createSchema', 'SchemaController@create');

Route::get('/listSchema', 'SchemaController@showAll');

Route::post('/{schemaname}/add-data', 'SchemaController@store');

Route::get('/{schemaname}/get-data', 'SchemaController@show');

Route::post('/{schemaname}/update-data', 'SchemaController@update');

Route::delete('/{schemaname}/delete-data', 'SchemaController@deleteRow');

Route::delete('/{schemaname}/dropSchema', 'SchemaController@dropTable');