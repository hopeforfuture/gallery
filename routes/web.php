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
Route::post('/signup', 'Auth\UserLoginController@signupprocess')->name('user.signup.submit');
Route::get('/dashboard', 'HomeController@dashboard')->name('user.dashboard');
Route::get('/reset/password', 'Auth\UserLoginController@resetpassword')->name('user.reset.password');
Route::post('/reset/password', 'Auth\UserLoginController@resetpasswordprocess')->name('user.reset.password.submit');

Route::prefix('album')->group(function() {
	Route::get('/list', 'AlbumController@index')->name('album.index');
	Route::get('/create', 'AlbumController@create')->name('album.create');
	Route::post('/insert', 'AlbumController@store')->name('album.insert');
	Route::get('/edit/{id}', 'AlbumController@edit')->name('album.edit');
	Route::post('/update/{id}', 'AlbumController@update')->name('album.update');
	Route::get('/remove/{id}', 'AlbumController@remove')->name('album.delete');
	Route::get('/upload/{id}', 'PhotoController@upload')->name('album.upload');
	Route::post('/upload/{id}', 'PhotoController@saveimages')->name('album.upload.submit');
	Route::get('/view/images/{id}', 'PhotoController@viewlist')->name('album.view.list');
	Route::get('/image/edit/{id}', 'PhotoController@edit')->name('album.photo.edit');
	Route::post('/image/update/{id}', 'PhotoController@update')->name('album.photo.update');
	Route::get('/image/delete/{id}', 'PhotoController@remove')->name('album.photo.delete');
});

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
