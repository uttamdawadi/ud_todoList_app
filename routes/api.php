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


Route::prefix('tasks')->group(function (){
    Route::get('/', 'App\Http\Controllers\Api\TaskController@index');
    Route::post('/new', 'App\Http\Controllers\Api\TaskController@store');
    Route::put('/complete', 'App\Http\Controllers\Api\TaskController@complete');
    Route::delete('/{task_id}', 'App\Http\Controllers\Api\TaskController@delete');
    Route::get('/filter', 'App\Http\Controllers\Api\TaskController@filter');
});

