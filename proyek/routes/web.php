<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route("home");
});

Route::prefix("ChicOut")->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/SignIn-SignUp', [LoginController::class, "getLoginPage"])->name("login");
        Route::post('/SignIn-SignUp', [LoginController::class, "loginRegister"]);
    });

    //All user route
    Route::controller(UserController::class)->group(function () {
        Route::get('/', "getHomePage")->name('home');

        Route::get('/Category', function () {
            // Avoid redirecting back to this route
            return redirect('ChicOut/Category/Men');    
        })->name('category');

        // Route::get('/Category/{nama}', "getCategoryPage");
        
        // Route::get('ChicOut/Category/{categoryName}', 'getCategoryPage')
        // ->where('categoryName', '[A-Za-z]+')
        // ->name('category.dynamic');

        Route::get('/Item/{id}', "getDetailPage")->name('detail');
        Route::post('/Item/{id}', "postDetailPage")->middleware("custom:user,master");

        Route::get('/Category/{nama}', "getCategoryPage");

        Route::get('/Cart', "getCartPage")->middleware("custom:user,master")->name('cart');
        Route::post('/Cart', "postCartPage");

        Route::get('/update-cart', function () {
            return redirect()->route('cart');
        });
        Route::post('/update-cart', 'updateCart')->name('update-cart');

        Route::get('/Profile', "getProfilePage")->middleware("custom:user,master")->name('profile');
        Route::post('/Profile', "postProfilePage")->middleware("custom:user,master");

        Route::get('/Profile/History/{id}', "getDtrans")->middleware("custom:user,master")->name('dtrans');

    });

    Route::prefix("Master")->group(function () {
        Route::controller(MasterController::class)->group(function () {
            Route::get('/', "getMasterHome")->middleware("custom:master")->name('master-home');
            Route::post('/', "postMasterHome")->middleware("custom:master");

            Route::get('/ReportItem', [ExportController::class ,"excelItems"])->middleware("custom:master")->name('master-report-item');

            Route::get('/Transaction', function () {
                return redirect()->route('master-home');
            });
            Route::get('/Transaction/{id}', "getDtrans")->middleware("custom:master")->name('master-dtrans');

            Route::get('/Category', function () {
                return redirect()->route('master-category');
            });
            Route::get('/Category/All', "getCategory")->middleware("custom:master")->name('master-category');
            Route::get('/Category/{id}', "getCategoryCRU")->middleware("custom:master")->name('master-category-cru');
            Route::post('/Category/{id}', "postCategoryCRU")->middleware("custom:master");
            Route::get('/Category/Delete-Recover/{id}', "deleteCategory")->middleware("custom:master")->name('master-delete-category');

            Route::get('/Payment', function () {
                return redirect()->route('master-payment');
            });
            Route::get('/Payment/All', "getPayment")->middleware("custom:master")->name('master-payment');
            Route::get('/Payment/{id}', "getPaymentCRU")->middleware("custom:master")->name('master-payment-cru');
            Route::post('/Payment/{id}', "postPaymentCRU")->middleware("custom:master");
            Route::get('/Payment/Delete-Recover/{id}', "deletePayment")->middleware("custom:master")->name('master-delete-payment');

            Route::get('/Item', function () {
                return redirect()->route('master-item', ['name' => 'All']);
            });
            Route::get('/Item/Category/{name}', "getItems")->middleware("custom:master")->name('master-item');
            Route::get('/Item/{id}', "getInsertUpdate")->middleware("custom:master")->name('master-insert-update');
            Route::post('/Item/{id}', "postInsertUpdate")->middleware("custom:master");
            Route::get('/Item/Delete-Recover/{id}', "doDelete")->middleware("custom:master")->name('master-delete-recover');

            Route::get('/Account', function () {
                return redirect()->route('master-account', ['search' => 'All']);
            });
            Route::get('/Account/{search}', "getAccount")->middleware("custom:master")->name('master-account');
            Route::get('/Account/Change-Role/{username}', "doRole")->middleware("custom:master")->name('master-account-role');


            Route::get('/Profile', "getProfile")->middleware("custom:master")->name('master-profile');
            Route::post('/Profile', "postProfile")->middleware("custom:master");
        });
    });

    Route::middleware('auth')->group(function () {
        Route::get('/logout', [LoginController::class, "logoutAccount"])->name('logout');
    });
});
    