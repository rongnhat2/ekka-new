<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Customer\DisplayController@index');


// Route::get('/admin', 'Admin\DisplayController@index');
// Route::get('/admin/category', 'Admin\CategoryController@index');
// Route::post('/admin/category/store', 'Admin\CategoryController@store');

Route::prefix('admin')->group(
    function () {
        Route::get('/', 'Admin\DisplayController@index');

        Route::prefix('category')->group(function () {
            Route::get('/', 'Admin\CategoryController@index');
            Route::post('store', 'Admin\CategoryController@store');
        });
    }
);


// Route::prefix('category')->group(function () {
//     Route::get('get', 'Admin\CategoryController@get')->name('admin.category.get');
//     Route::get('/get-one/{id}', 'Admin\CategoryController@get_one')->name('admin.category.get_one');
//     Route::post('store', 'Admin\CategoryController@store')->name('admin.category.store');
//     Route::post('/update', 'Admin\CategoryController@update')->name('admin.category.update');
//     Route::get('/delete/{id}', 'Admin\CategoryController@delete')->name('admin.category.delete');
// });