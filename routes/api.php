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

//Route::post('/tasks', [TaskController::class, 'store']);
//Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks', 'App\Http\Controllers\Api\TaskController@index');
//Route::put('/tasks/complete', [TaskController::class, 'complete']);
//Route::delete('/tasks/{task}', [TaskController::class, 'delete']);
//Route::get('/tasks/filter', [TaskController::class, 'filter']);
