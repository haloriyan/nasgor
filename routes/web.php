<?php

use App\Http\Controllers\AddOnController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\User;
use Illuminate\Support\Facades\Route;

Route::match(['GET', 'POST'], '/', [UserController::class, 'login'])->name('login');
Route::match(['GET', 'POST'], 'login', [UserController::class, 'login'])->name('login');
Route::group(['middleware' => "User"], function () {
    Route::get('dashboard/{branchID?}', [UserController::class, 'dashboard'])->name('dashboard');
    Route::group(['prefix' => "branch"], function () {
        Route::get('switch/{id}', [UserController::class, 'switchBranch'])->name('branch.switch');
        Route::get('settings', [UserController::class, 'branchSettings'])->name('branch.settings');
        Route::post('settings/save', [BranchController::class, 'saveSettings'])->name('branch.settings.save');
    });

    Route::group(['prefix' => "branches"], function () {
        Route::group(['prefix' => "{id}"], function () {
            Route::post('basic-info', [BranchController::class, 'basicInfo'])->name('branches.basicInfo');
            Route::get('detail', [BranchController::class, 'detail'])->name('branches.detail');
        });
        Route::post('delete', [BranchController::class, 'delete'])->name('branches.delete');
        Route::post('store', [BranchController::class, 'store'])->name('branches.store');
        Route::get('/', [UserController::class, 'branches'])->name('branches');
    });
    
    Route::group(['prefix' => "inventori"], function () {
        Route::group(['prefix' => "{id}"], function () {
            Route::post('update-notes', [InventoryController::class, 'updateNotes'])->name('inventory.detail.updateNotes');
            Route::post('update-supplier', [InventoryController::class, 'updateSupplier'])->name('inventory.detail.updateSupplier');
            Route::post('update-branch', [InventoryController::class, 'updateBranchDestination'])->name('inventory.detail.updateBranchDestination');
            Route::post('update-quantity', [InventoryController::class, 'updateQuantity'])->name('inventory.detail.updateQuantity');
            Route::get('remove-product/{itemID}', [InventoryController::class, 'removeProduct'])->name('inventory.detail.removeProduct');
            Route::post('add-product', [InventoryController::class, 'addProduct'])->name('inventory.detail.addProduct');
            Route::get('proceed', [InventoryController::class, 'proceed'])->name('inventory.proceed');
            Route::get('detail', [InventoryController::class, 'detail'])->name('inventory.detail');
        });

        Route::post('store', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/', [UserController::class, 'inventory'])->name('inventory');
    });

    Route::group(['prefix' => "produk"], function () {
        Route::group(['prefix' => "category"], function () {
            Route::post('store', [CategoryController::class, 'store'])->name('product.category.store');
            Route::post('delete', [CategoryController::class, 'delete'])->name('product.category.delete');
            Route::get('{id}/toggle-pos', [CategoryController::class, 'togglePos'])->name('product.category.togglePos');
            Route::get('{id}/toggle-requestable', [CategoryController::class, 'toggleRequestable'])->name('product.category.toggleRequestable');
            Route::get('{id}/priority/{action}', [CategoryController::class, 'priority'])->name('product.category.priority');
        });

        Route::group(['prefix' => "addon"], function () {
            Route::post('update', [AddOnController::class, 'update'])->name('product.addon.update');
            Route::group(['prefix' => "{id}"], function () {
                Route::post('add-product', [AddOnController::class, 'addProduct'])->name('product.addon.addProduct');
                Route::post('delete', [AddOnController::class, 'delete'])->name('product.addon.delete');
            });
            Route::post('store', [AddOnController::class, 'store'])->name('product.addon.store');
        });

        Route::group(['prefix' => "{id}"], function () {
            Route::post('image/store', [ProductController::class, 'storeImage'])->name('product.detail.image.store');
            Route::get('image/delete/{imageID}', [ProductController::class, 'deleteImage'])->name('product.detail.image.delete');

            Route::get('priority/{action}', [ProductController::class, 'priority'])->name('product.priority');

            Route::group(['prefix' => "ingredient"], function () {
                Route::post('store', [ProductController::class, 'storeIngredient'])->name('product.detail.ingredient.store');
                Route::get('{ingredientID}/delete', [ProductController::class, 'deleteIngredient'])->name('product.detail.ingredient.delete');
            });
            Route::group(['prefix' => "addon"], function () {
                Route::get('{addOnID}', [ProductController::class, 'removeAddOn'])->name('product.removeAddOn');
                Route::post('store', [ProductController::class, 'storeAddOn'])->name('product.detail.addon.store');
                Route::get('{pivotID}/delete', [ProductController::class, 'deleteAddOn'])->name('product.detail.addon.delete');
            });

            Route::post('update-info', [ProductController::class, 'updateInfo'])->name('product.detail.updateInfo');
            Route::post('add-price', [ProductController::class, 'addPrice'])->name('product.detail.addPrice');
            Route::get('remove-price/{priceID}', [ProductController::class, 'removePrice'])->name('product.detail.removePrice');
            Route::match(['get', 'post'], 'toggle-category/{categoryID?}', [ProductController::class, 'toggleCategory'])->name('product.detail.category.toggle');
            Route::get('detail', [ProductController::class, 'detail'])->name('product.detail');
        });
        Route::post('store', [ProductController::class, 'store'])->name('product.store');
        Route::post('delete', [ProductController::class, 'delete'])->name('product.delete');
        Route::get('/', [UserController::class, 'product'])->name('product');
    });

    Route::group(['prefix' => "customer"], function () {
        Route::group(['prefix' => "type"], function () {
            Route::group(['prefix' => "{typeID}"], function () {
                Route::post('add', [CustomerController::class, 'addCustomerToType'])->name('customer.type.addCustomer');
                Route::get('remove/{customerID}', [CustomerController::class, 'removeCustomerFromType'])->name('customer.type.remove');
            });
            Route::post('rename', [CustomerController::class, 'renameType'])->name('customer.type.rename');
            Route::post('delete', [CustomerController::class, 'deleteType'])->name('customer.type.delete');
            Route::post('store', [CustomerController::class, 'storeType'])->name('customer.type.store');
        });
        Route::get('{id}/delete', [CustomerController::class, 'delete'])->name('customer.delete');
        Route::post('store', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/', [UserController::class, 'customer'])->name('customer');
    });
    Route::group(['prefix' => "supplier"], function () {
        Route::post('store', [SupplierController::class, 'store'])->name('supplier.store');
        Route::post('delete', [SupplierController::class, 'delete'])->name('supplier.delete');
        Route::get('/', [UserController::class, 'supplier'])->name('supplier');
    });
    Route::group(['prefix' => "purchasing"], function () {
        Route::group(['prefix' => "{id}"], function () {
            Route::post('update-notes', [PurchasingController::class, 'updateNotes'])->name('purchasing.detail.updateNotes');
            Route::post('update-supplier', [PurchasingController::class, 'updateSupplier'])->name('purchasing.detail.updateSupplier');
            Route::post('update-quantity', [PurchasingController::class, 'updateQuantity'])->name('purchasing.detail.updateQuantity');
            Route::post('add-product', [PurchasingController::class, 'addProduct'])->name('purchasing.detail.addProduct');
            Route::get('remove-product/{itemID}', [PurchasingController::class, 'removeProduct'])->name('purchasing.detail.removeProduct');
            Route::get('detail', [PurchasingController::class, 'detail'])->name('purchasing.detail');
            Route::post('receive', [PurchasingController::class, 'receive'])->name('purchasing.receive');
        });

        Route::post('store', [PurchasingController::class, 'store'])->name('purchasing.store');
        Route::get('/', [UserController::class, 'purchasing'])->name('purchasing');
    });

    Route::group(['prefix' => "sales"], function () {
        Route::group(['prefix' => "{id}"], function () {
            // Route::get('add-product', [SalesController::class, 'detail'])->name('sales.detail.product.add');
            Route::group(['prefix' => "product"], function () {
                Route::post('store', [SalesController::class, 'storeProduct'])->name('sales.detail.product.store');
                Route::get('delete/{pivotID}', [SalesController::class, 'deleteProduct'])->name('sales.detail.product.delete');
            });

            Route::get('toggle-payment-status', [SalesController::class, 'togglePaymentStatus'])->name('sales.detail.togglePaymentStatus');
            Route::post('update-notes', [SalesController::class, 'updateNotes'])->name('sales.detail.updateNotes');
            Route::post('update-customer', [SalesController::class, 'updateCustomer'])->name('sales.detail.updateCustomer');
            Route::get('proceed', [SalesController::class, 'proceed'])->name('sales.proceed');
            Route::get('detail', [SalesController::class, 'detail'])->name('sales.detail');
        });
        Route::post('store', [SalesController::class, 'store'])->name('sales.store');
        Route::get('/', [UserController::class, 'sales'])->name('sales');
    });

    Route::group(['prefix' => "pengaturan"], function () {
        Route::get('umum', [UserController::class, 'generalSettings'])->name('settings');
    });
    Route::group(['prefix' => "staff"], function () {
        Route::post('store', [UserController::class, 'store'])->name('users.store');
        Route::post('update', [UserController::class, 'update'])->name('users.update');
        Route::post('delete', [UserController::class, 'delete'])->name('users.delete');
        Route::get('/', [UserController::class, 'users'])->name('users');
    });
    Route::group(['prefix' => "hak-akses"], function () {
        Route::post('store', [RoleController::class, 'store'])->name('accessRole.store');
        Route::post('assign', [RoleController::class, 'assignAccess'])->name('accessRole.assign');
        Route::get('remove/{userBranchID}', [RoleController::class, 'removeAccess'])->name('accessRole.removeAccess');
        Route::get('{id}/detail', [RoleController::class, 'detail'])->name('accessRole.detail');
        Route::get('{roleID}/toggle/{permissionID}', [RoleController::class, 'togglePermission'])->name('accessRole.togglePermission');
        Route::get('/', [UserController::class, 'accessRole'])->name('accessRole');
    });

    Route::group(['prefix' => "sales_report"], function () {
        Route::post('test', [ReportController::class, 'test'])->name('sales_report.test');
        Route::get('top-selling', [ReportController::class, 'topSellingReport'])->name('sales_report.topSelling');
        Route::get('detail', [ReportController::class, 'salesDetailReport'])->name('sales_report.detail');
        Route::get('/{branchID?}', [ReportController::class, 'salesReport'])->name('sales_report');
    });
    Route::group(['prefix' => "expense_report"], function () {
        Route::post('test', [ReportController::class, 'test'])->name('expense_report.test');
        Route::get('/', [ReportController::class, 'expense_report'])->name('expense_report');
    });
    Route::group(['prefix' => "purchasing_report"], function () {
        Route::post('test', [ReportController::class, 'test'])->name('purchasing_report.test');
        Route::get('/', [ReportController::class, 'purchasingReport'])->name('purchasing_report');
    });
    Route::group(['prefix' => "movement_report"], function () {
        Route::get('{productID}/detail', [ReportController::class, 'stockMovementDetail'])->name('movement_report.detail');
        Route::get('/', [ReportController::class, 'stockMovement'])->name('movement_report');
    });
    Route::group(['prefix' => "stock_request"], function () {
        Route::post('store', [InventoryController::class, 'stockRequestStore'])->name('stock_request.store');
        Route::get('{requestID}/reject', [InventoryController::class, 'stockRequestReject'])->name('stock_request.reject');
        Route::post('accept', [StockController::class, 'stockRequestAccept'])->name('stock_request.accept');
        Route::get('/', [UserController::class, 'stockRequest'])->name('stock_request');
    });

    Route::group(['prefix' => "stock_order"], function () {
        Route::post('store', [UserController::class, 'store'])->name('stock_order.store');
        Route::get('/', [UserController::class, 'stock_order'])->name('stock_order');
    });
    Route::group(['prefix' => "stock_order_taker"], function () {
    Route::post('store', [UserController::class, 'store'])->name('stock_order_taker.store');
        Route::get('/', [UserController::class, 'stock_order_taker'])->name('stock_order_taker');
    });

    Route::group(['prefix' => "absensi"], function () {
        // Route::post('/', [UserController::class, 'delete'])->name('checkin.delete');
        Route::group(['prefix' => "{id}"], function () {
            Route::get('detail', [CheckInController::class, 'detail'])->name('checkin.detail');
        });
        Route::get('/', [UserController::class, 'checkin'])->name('checkin');
    });

    Route::group(['prefix' => "profil"], function () {
        Route::post('save', [UserController::class, 'updateProfile'])->name('profile.save');
        Route::get('/', [UserController::class, 'profile'])->name('profile');
    });
});

// Route::get('', [SalesController::class, 'invoice'])->name('invoice');
Route::group(['prefix' => "invoice/{invoice_number}"], function () {
    Route::post('review', [ReviewController::class, 'store'])->name('invoice.review');
    Route::get('/', [SalesController::class, 'invoice'])->name('invoice');
});

Route::get('error/{code}', [UserController::class, 'errorPage'])->name('errorPage');