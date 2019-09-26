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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function(){
	Route::group(['prefix' => 'item'], function(){

		Route::get('/', 'ItemController@index');

		Route::group(['prefix' => 'type'], function(){
			Route::get('/', 'ItemTypeController@itemTypes');
			Route::get('/{itemtype}', 'ItemTypeController@getItemType');
			Route::post('/{itemtype?}', 'ItemTypeController@saveItemType');
		});

		Route::group(['prefix' => '{item?}'], function(){
				Route::get('/', 'ItemController@getItem');
				Route::post('/', 'ItemController@saveItem');
				Route::group(['prefix' => 'stock'], function(){
					Route::get('/', 'ItemController@getItemStockDetails');
					Route::get('/{itemstockdetail}', 'ItemController@getItemStockDetail');
					Route::post('/{itemstockdetail?}', 'ItemController@saveItemStockDetail');
				});
		});
	});
});

