<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Api\CategoryController;
use App\Http\Controllers\Backend\Api\PostController;
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
Route::group(['middleware' => ['auth:sanctum']], function () {  
    ## Route khoá học ##
    Route::group(['prefix' => 'post'], function () {
        Route::get('list', [PostController::class, 'index']);
        Route::post('create', [PostController::class, 'create']);
        Route::post('update/{id}', [PostController::class, 'update']);
        Route::post('delete/{id}', [PostController::class, 'destroy']);
    });
    Route::group(['prefix' => 'category'], function () {
        Route::get('list', [CategoryController::class, 'index']);
        Route::post('create', [CategoryController::class, 'create']);
        Route::post('update/{id}', [CategoryController::class, 'update']);
        Route::post('delete/{id}', [CategoryController::class, 'destroy']);
    });
});