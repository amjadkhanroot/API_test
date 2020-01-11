<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'Api\AuthController@register');

Route::post('login', 'Api\AuthController@login');

Route::middleware('auth:api', 'throttle:10:1') -> prefix('user') -> group( function (){
    Route::post('update/password', 'Api\UserController@updatePassword');
    Route::post('update/profile', 'Api\UserController@updateProfile');
});

// Using Resource for category
Route::resource('categories', 'Api\CategoryController');
Route::put('categories/{categoryId}/restore', 'Api\CategoryController@restore')->name('categories.restore');
Route::delete('categories/{categoryId}/force-delete', 'Api\CategoryController@forceDelete')->name('categories.forceDelete');

// Task
Route::resource('tasks', 'Api\TaskController');
Route::put('tasks/{taskId}/restore', 'Api\TaskController@restore')->name('tasks.restore');
Route::delete('tasks/{taskId}/force-delete', 'Api\TaskController@forceDelete')->name('tasks.forceDelete');

// File Upload
Route::post('tasks/{taskId}/file-upload', 'Api\FileUploadController@upload')->name('fileUploads.upload');
Route::delete('files/{file}', 'Api\FileUploadController@destroy')->name('fileUploads.delete');

// Comment
Route::resource('comments', 'Api\CommentController');




// Without using Resource for category
//Route::middleware('auth:api') -> prefix('category') -> group( function () {
//    Route::post('create', 'Api\CategoryController@createCategory');
//    Route::put('update', 'Api\CategoryController@updateCategory');
//    Route::delete('delete', 'Api\CategoryController@deleteCategory');
//    Route::get('get', 'Api\CategoryController@getCategory');
//});


