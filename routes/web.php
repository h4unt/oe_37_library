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

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
})->name('locale');

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::name('admin.')
    ->prefix('admin')
    ->middleware(['auth', 'can:accessAdmin'])
    ->group(function () {
    Route::get('dashboard', 'DashboardController@index')->name('index');   
    Route::resources([
        'users' => 'UserController',
        'authors' => 'AuthorController',
        'publishers' => 'PublisherController',
        'books' => 'BookController',
        'reviews' => 'ReviewController',
    ]);
    
    Route::resource('categories', 'CategoryController', ['except' => [
        'show'
    ]]); 

    Route::get('publishers-export', 'PublisherController@export')->name('publishers.export');
    Route::get('authors-export', 'AuthorController@export')->name('authors.export');   
    Route::get('publishers-export', 'PublisherController@export')->name('publishers.export');
    Route::get('authors-export', 'AuthorController@export')->name('authors.export');
    Route::get('users-export', 'UserController@export')->name('users.export');
});
Route::post('reviews', 'ReviewController@store')->name('reviews.store');
Route::get('book/detail/{id}', 'BookController@show')->name('book.detail');
Route::post('book/like', 'BookController@like')->name('book.like');
Route::post('book/borrow/{id}', 'BookController@borrow')->name('book.borrow')->middleware('auth');
Route::get('author/detail/{id}', 'AuthorController@show')->name('authors.detail');
Route::get('authors', 'AuthorController@showForUser')->name('authors.showAll');
Route::get('publisher/detail/{id}', 'PublisherController@show')
    ->name('publisher.detail');
Route::get('publishers', 'PublisherController@showAll')->name('publishers.showAll');
