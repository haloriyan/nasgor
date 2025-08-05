<?php

use App\Http\Controllers\Api\CheckInController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PosController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PurchasingController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\UserController;
use App\Models\Purchasing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => "customer"], function () {
    Route::post('store', [CustomerController::class, 'store']);
    Route::get('search', [CustomerController::class, 'search']);
});
Route::get('user/search', [UserController::class, 'search']);
Route::get('product/search', [ProductController::class, 'search']);
Route::get('suppliers', [UserController::class, 'suppliers']);

Route::group(['prefix' => "user"], function () {
    Route::get('t', function () {
        return "Hello";
    });
    Route::get('auth', [UserController::class, 'auth']);
    Route::get('switch-branch/{accessID}', [UserController::class, 'switchBranch']);
    Route::post('login', [UserController::class, 'login']);
    
    Route::group(['prefix' => "check-in"], function () {
        Route::get('history', [CheckInController::class, 'history']);
        Route::post('/', [CheckInController::class, 'check']);
    });

    Route::group(['prefix' => "branch"], function () {
        Route::post('switch', [UserController::class, 'switchBranch']);
    });
});

Route::get('dashboard', [UserController::class, 'dashboard']);

Route::group(['prefix' => "pos"], function () {
    Route::post('store', [PosController::class, 'store']);
    Route::get('/', [PosController::class, 'index']);
});

Route::group(['prefix' => "sales"], function () {
    Route::get('/', [UserController::class, 'sales']);
});
Route::group(['prefix' => "purchasing"], function () {
    Route::group(['prefix' => "{id}"], function () {
        Route::post('update-notes', [PurchasingController::class, 'updateNotes']);
        Route::post('update-supplier', [PurchasingController::class, 'updateSupplier']);
        Route::post('add-product', [PurchasingController::class, 'addProduct']);
        Route::post('remove-product/{itemID}', [PurchasingController::class, 'removeProduct']);
        Route::post('update', [PurchasingController::class, 'update']);
        Route::get('detail', [PurchasingController::class, 'detail']);
        Route::post('publish', [PurchasingController::class, 'publish']);
    });
    Route::post('store', [PurchasingController::class, 'store']);
    Route::get('/', [UserController::class, 'purchasing']);
});

Route::group(['prefix' => "opname"], function () {
    Route::group(['prefix' => "{id}"], function () {
        Route::post('add-product', [StockController::class, 'addProduct']);
        Route::post('remove-product/{itemID}', [StockController::class, 'removeProduct']);
        Route::post('update-notes', [StockController::class, 'updateNotes']);
        Route::get('detail', [StockController::class, 'detail']);
        Route::post('publish', [StockController::class, 'publish']);
    });

    Route::get('/', [UserController::class, 'opname']);
});