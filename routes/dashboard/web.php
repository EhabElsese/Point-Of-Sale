<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {

        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {

            Route::get('/', 'WelcomeController@index')->name('welcome');

            //category routes
            Route::resource('categories', 'CategoryController')->except(['show']);

            //product routes
            Route::resource('products', 'ProductController')->except(['show']);

            //client routes
            Route::resource('clients', 'ClientController')->except(['show']);
            Route::resource('clients.orders', 'Client\OrderController')->except(['show']);

            //order routes
            Route::resource('orders', 'OrderController');
            Route::get('/orders/{order}/products', 'OrderController@products')->name('orders.products');
            Route::post('/orders/updateStatus', 'OrderController@updateStatus')->name('orders.update_status');


            //Report routes
            Route::get('monthly-report', 'ReportController@monthlyReport')->name('monthlyReport');

            //Revenue routes
            Route::resource('revenues', 'RevenueController')->only(['index']);

            //user routes
            Route::resource('users', 'UserController')->except(['show']);

        });//end of dashboard routes
    });


