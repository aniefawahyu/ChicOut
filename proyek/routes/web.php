<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;

Route::get(uri: '/', action: function () {
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

        Route::get('/Item/{id}', "getDetailPage")->name('detail');
        Route::post('/Item/{id}', "postDetailPage")->middleware("custom:user,master");

        Route::get('/Item/collaboration/{categoryId}/{id}', "getCollaborationDetailPage")->name('collaboration-detail');

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

        Route::get('/return-items/{id}', 'showReturnForm')->name('return-items');
        Route::post('/return', 'processReturn')->name('process-return');
    });

    Route::controller(TransactionController::class)->group(function () {
        Route::post('/checkout', 'handleTransaction')->name('checkout');
        Route::post('/validate-payment', 'handlePaymentValidation')->name('validate-payment');
    });

    Route::prefix("Master")->group(function () {
        Route::controller(MasterController::class)->group(function () {
            Route::get('/', "getMasterHome")->name('master-home');
            Route::post('/', "postMasterHome");

            Route::get('/ReportItem', [ExportController::class, "excelItems"])->name('master-report-item');

            Route::get('/Transaction', function () {
                return redirect()->route('master-home');
            });
            Route::get('/Transaction/{id}', "getDtrans")->name('master-dtrans');

            Route::get('/Category', function () {
                return redirect()->route('master-category');
            });
            Route::get('/Category/All', "getCategory")->name('master-category');
            Route::get('/Category/{id}', "getCategoryCRU")->name('master-category-cru');
            Route::post('/Category/{id}', "postCategoryCRU");
            Route::get('/Category/Delete-Recover/{id}', "deleteCategory")->name('master-delete-category');

            Route::get('/Payment', function () {
                return redirect()->route('master-payment');
            });
            Route::get('/Payment/All', "getPayment")->name('master-payment');
            Route::get('/Payment/{id}', "getPaymentCRU")->name('master-payment-cru');
            Route::post('/Payment/{id}', "postPaymentCRU");
            Route::get('/Payment/Delete-Recover/{id}', "deletePayment")->name('master-delete-payment');

            Route::get('/Item', function () {
                return redirect()->route('master-item', ['name' => 'All']);
            });
            Route::get('/Item/Category/{name}', "getItems")->name('master-item');
            Route::get('/Item/{id}', "getInsertUpdate")->name('master-insert-update');
            Route::post('/Item/{id}', "postInsertUpdate");
            Route::get('/Item/Delete-Recover/{id}', "doDelete")->name('master-delete-recover');

            Route::get('/Account', function () {
                return redirect()->route('master-account', ['search' => 'All']);
            });
            Route::get('/Account/{search}', "getAccount")->name('master-account');
            Route::get('/Account/Change-Role/{username}', "doRole")->name('master-account-role');


            Route::get('/Profile', "getProfile")->name('master-profile');
            Route::post('/Profile', "postProfile");

            Route::get('/Brand', function () {
                return redirect()->route('master-brand');
            });
            Route::get('/Brand/All', "getBrand")->name('master-brand');
            Route::get('/Brand/{id}', "getBrandCRU")->name('master-brand-cru');
            Route::post('/Brand/{id}', "postBrandCRU");
            Route::get('/Brand/Delete-Recover/{id}', "deleteBrand")->name('master-delete-brand');
        });
        // Route::get('/Category/All', function (Request $req) {
        //     if (Auth::check() && Auth::user()->password === 'staff123') {
        //         return view('master.category');
        //     }

        //     return redirect()->route('master-home')->with('error', 'Access Denied');
        // });

        // Route::get('/Item/Category/{name}', function (Request $req) {
        //     if (Auth::check() && Auth::user()->password === 'staff123') {
        //         return view('master.items', ['name' => $req->name]);
        //     }

        //     return redirect()->route('master-home')->with('error', 'Access Denied');
        // });
        // Route::get('/', function () {
        //     if (Auth::check() && Auth::user()->password === 'staff123') {
        //         return redirect()->route('master-home');
        //     }

        //     return view('master.home');
        // });
        // Route::get('/Profile/Edit', function () {
        //     if (Auth::check() && Auth::user()->password === 'staff123') {

        //         return view('master.edit-profile', ['user' => Auth::user()]);
        //     }


        //     return redirect()->route('master-home')->with('error', 'Access Denied');
        // })->name('master-edit-profile');

    });

    Route::middleware('auth')->group(function () {
        Route::get('/logout', [LoginController::class, "logoutAccount"])->name('logout');
    });
});
