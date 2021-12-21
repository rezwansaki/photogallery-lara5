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
Route::get('/demo', 'TestController@index');
Route::get('/', 'PhotoGallary@index'); // '/' means root. first run this route. 

Auth::routes();

Route::get('/album/id/{id}', 'PhotoGallary@selectedAlbum');
Route::get('/image/id/{id}', 'PhotoGallary@singleImage');
Route::get('/search', 'PhotoGallary@search');

//admin controll 
Route::get('/dashboard', 'PhotoGallary@dashboard');
Route::get('/image/id/{id}/edit', 'PhotoGallary@edit');
Route::post('/image/id/{id}/update', 'PhotoGallary@update');
Route::get('/image/create', 'PhotoGallary@create');
Route::post('/image/store', 'PhotoGallary@store');
Route::delete('/image/id/{id}/destroy', 'PhotoGallary@destroy');

Route::delete('/admin/deleteallimages', 'PhotoGallary@deleteAllImages'); //delete all images at a time 

Route::get('/album/create', 'PhotoGallary@createalbum');
Route::post('/album/store', 'PhotoGallary@storeAlbum');
Route::get('/album/showAlbum', 'PhotoGallary@showAlbum');
Route::get('/album/edit/{id}', 'PhotoGallary@editAlbum');
Route::post('/album/update/{id}', 'PhotoGallary@updateAlbum');
Route::delete('/album/id/{id}/destroy', 'PhotoGallary@destroyAlbum');

//profile 
Route::get('/editProfile', 'PhotoGallary@editProfile');
Route::post('/updateProfile', 'PhotoGallary@updateProfile');

//admin - reset : reset the project as like at the first time - factory reset - to use new one 
Route::delete('/admin/reset', 'PhotoGallary@resetApp');

//admin - settings : such as project name, maximum uploaded file size, email for password reset system 
Route::get('/admin/showSettings', 'PhotoGallary@showSettings'); //to show the settings blade page 
Route::post('/admin/doSettings/{id}/update', 'PhotoGallary@doSettings'); //to update data from settings table 
