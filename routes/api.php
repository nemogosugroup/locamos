<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\App;

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
Route::post('user/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('/check-login', [AuthController::class, 'checkLogin']);
});
Route::post('locale', function () {
    // Validate
    $validated = request()->validate([
        'language' => ['required'],
    ]);
    // Set locale
    App::setLocale($validated['language']);
    // Session
    session()->put('locale', $validated['language']);
    // Response
    return redirect()->back();
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// private routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('create', [AuthController::class, 'create']);
        Route::post('update/{id}', [AuthController::class, 'update']);
        Route::post('export', [AuthController::class, 'export']);
        Route::get('info', [AuthController::class, 'info']);
        Route::get('training-info', [AuthController::class, 'trainingInfo']);
        Route::get('training-detail-info', [AuthController::class, 'trainingDetailInfo']);
        Route::get('roles', [AuthController::class, 'roles']);
        Route::get('list', [AuthController::class, 'list']);
        Route::get('list-employee', [AuthController::class, 'employee']);
        Route::get('store', [AuthController::class, 'store']);
        Route::post('add', [AuthController::class, 'add']);
        Route::get('get', [AuthController::class, 'getCourseEquipment']);
        Route::get('/', [AuthController::class, 'user']);
    });
    Route::group(['prefix' => 'role'], function () {
        Route::get('list', [RoleController::class, 'list']);
    });
});