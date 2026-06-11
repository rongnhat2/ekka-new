<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['ShareCustomerView'])->group(function () {
    Route::get('/', 'Customer\DisplayController@index')->name('customer.view.index');
    Route::get('category', 'Customer\ProductController@category')->name('customer.view.category');
    Route::get('product/{id}', 'Customer\ProductController@show')->name('customer.view.product');
    Route::get('cart', 'Customer\CartController@index')->name('customer.view.cart');
    Route::post('cart/add', 'Customer\CartController@add')->name('customer.cart.add');
    Route::post('cart/update', 'Customer\CartController@update')->name('customer.cart.update');
    Route::get('cart/remove/{varId}', 'Customer\CartController@remove')->name('customer.cart.remove');

    Route::middleware(['AuthCustomer:auth'])->group(function () {
        Route::get('login', 'Customer\DisplayController@login')->name('customer.view.login');
        Route::get('register', 'Customer\DisplayController@register')->name('customer.view.register');
        Route::post('register', 'Customer\AuthController@register')->name('customer.register');
        Route::post('login', 'Customer\AuthController@login')->name('customer.login');
    });

    Route::middleware(['AuthCustomer:logined'])->group(function () {
        Route::post('logout', 'Customer\AuthController@logout')->name('customer.logout');
        Route::get('profile', 'Customer\ProfileController@index')->name('customer.view.profile');
        Route::post('profile', 'Customer\ProfileController@update')->name('customer.profile.update');
        Route::get('order/{id}', 'Customer\ProfileController@orderDetail')->name('customer.view.order');
        Route::get('checkout', 'Customer\CheckoutController@index')->name('customer.view.checkout');
        Route::post('checkout', 'Customer\CheckoutController@store')->name('customer.checkout.store');
    });
});

Route::middleware(['AuthAdmin:auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/login', 'Admin\DisplayController@login')->name('admin.login');
        Route::post('/login', 'Admin\AuthController@login')->name('admin.login.post');
    });
});

Route::middleware(['AuthAdmin:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', 'Admin\DisplayController@index')->name('admin.index');
        Route::get('statistic', 'Admin\StatisticController@index')->name('admin.statistic');

        Route::prefix('category')->group(function () {
            Route::get('/', 'Admin\CategoryController@index')->name('admin.category.index');
            Route::post('store', 'Admin\CategoryController@store')->name('admin.category.store');
            Route::post('update', 'Admin\CategoryController@update')->name('admin.category.update');
            Route::post('delete/{id}', 'Admin\CategoryController@destroy')->name('admin.category.delete');
        });

        Route::prefix('color')->group(function () {
            Route::get('/', 'Admin\ColorController@index')->name('admin.color.index');
            Route::post('store', 'Admin\ColorController@store')->name('admin.color.store');
            Route::post('update', 'Admin\ColorController@update')->name('admin.color.update');
            Route::post('delete/{id}', 'Admin\ColorController@destroy')->name('admin.color.delete');
        });

        Route::prefix('size')->group(function () {
            Route::get('/', 'Admin\SizeController@index')->name('admin.size.index');
            Route::post('store', 'Admin\SizeController@store')->name('admin.size.store');
            Route::post('update', 'Admin\SizeController@update')->name('admin.size.update');
            Route::post('delete/{id}', 'Admin\SizeController@destroy')->name('admin.size.delete');
        });

        Route::prefix('brand')->group(function () {
            Route::get('/', 'Admin\BrandController@index')->name('admin.brand.index');
            Route::post('store', 'Admin\BrandController@store')->name('admin.brand.store');
            Route::post('update', 'Admin\BrandController@update')->name('admin.brand.update');
            Route::post('delete/{id}', 'Admin\BrandController@destroy')->name('admin.brand.delete');
        });

        Route::prefix('material')->group(function () {
            Route::get('/', 'Admin\MaterialController@index')->name('admin.material.index');
            Route::post('store', 'Admin\MaterialController@store')->name('admin.material.store');
            Route::post('update', 'Admin\MaterialController@update')->name('admin.material.update');
            Route::post('delete/{id}', 'Admin\MaterialController@destroy')->name('admin.material.delete');
        });

        Route::prefix('media')->group(function () {
            Route::get('/', 'Admin\MediaController@index')->name('admin.media.index');
            Route::get('list', 'Admin\MediaController@list')->name('admin.media.list');
            Route::post('store', 'Admin\MediaController@store')->name('admin.media.store');
            Route::post('delete/{id}', 'Admin\MediaController@destroy')->name('admin.media.delete');
        });

        Route::prefix('product')->group(function () {
            Route::get('/', 'Admin\ProductController@index')->name('admin.product.index');
            Route::get('{id}/data', 'Admin\ProductController@data')->name('admin.product.data');
            Route::post('store', 'Admin\ProductController@store')->name('admin.product.store');
            Route::post('update', 'Admin\ProductController@update')->name('admin.product.update');
            Route::post('delete/{id}', 'Admin\ProductController@destroy')->name('admin.product.delete');
        });

        Route::prefix('product-var')->group(function () {
            Route::get('/', 'Admin\ProductVarController@index')->name('admin.product_var.index');
            Route::post('store', 'Admin\ProductVarController@store')->name('admin.product_var.store');
            Route::post('update', 'Admin\ProductVarController@update')->name('admin.product_var.update');
            Route::post('delete/{id}', 'Admin\ProductVarController@destroy')->name('admin.product_var.delete');
        });

        Route::prefix('warehouse')->group(function () {
            Route::get('/', 'Admin\WarehouseController@index')->name('admin.warehouse.index');
            Route::get('history', 'Admin\WarehouseController@history')->name('admin.warehouse.history');
            Route::get('history/{id}', 'Admin\WarehouseController@show')->name('admin.warehouse.show');
            Route::get('product/{productId}/variants', 'Admin\WarehouseController@variants')->name('admin.warehouse.variants');
            Route::post('store', 'Admin\WarehouseController@store')->name('admin.warehouse.store');
        });

        Route::prefix('order')->group(function () {
            Route::get('/', 'Admin\OrderController@index')->name('admin.order.index');
            Route::get('create', 'Admin\OrderController@create')->name('admin.order.create');
            Route::post('store', 'Admin\OrderController@store')->name('admin.order.store');
            Route::post('update', 'Admin\OrderController@update')->name('admin.order.update');
            Route::get('{id}/data', 'Admin\OrderController@data')->name('admin.order.data');
        });

        Route::prefix('customer')->group(function () {
            Route::get('/', 'Admin\CustomerController@index')->name('admin.customer.index');
        });
    });
});
