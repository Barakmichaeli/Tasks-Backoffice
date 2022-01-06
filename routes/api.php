<?php

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

/*
 * Tasks REST API
 */
Route::prefix('tasks')->group(function () {
    Route::get('/', '\App\Http\Controllers\TasksController@index');
    Route::get('/{task}', '\App\Http\Controllers\TasksController@get');
    Route::post('/', '\App\Http\Controllers\TasksController@add');
    Route::patch('/{task}', '\App\Http\Controllers\TasksController@edit');
    Route::delete('/{task}', '\App\Http\Controllers\TasksController@delete');
});
