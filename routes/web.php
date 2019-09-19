<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'Auth\UserLoginController@showLoginForm')->name('user.login');
Route::post('/', 'Auth\UserLoginController@dologin')->name('user.login.submit');
Route::get('/logout', 'Auth\UserLoginController@logout')->name('user.logout');
Route::get('/signup', 'Auth\UserLoginController@signup')->name('user.signup');
Route::get('/dashboard', 'HomeController@dashboard')->name('user.dashboard');

Route::prefix('admin')->group(function() {
	Route::get('/', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
	Route::post('/login', 'Auth\AdminLoginController@dologin')->name('admin.login.submit');
	Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
	Route::get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
	
	Route::get('/category/create', 'CategoryController@create')->name('category.create');
	Route::post('/category/insert', 'CategoryController@store')->name('category.insert');
	Route::get('/category/list', 'CategoryController@index')->name('category.index');
	Route::get('/category/edit/{id}', 'CategoryController@edit')->name('category.edit');
	Route::post('/category/update/{id}', 'CategoryController@update')->name('category.update');
	Route::get('/category/remove/{id}', 'CategoryController@remove')->name('category.delete');
});
