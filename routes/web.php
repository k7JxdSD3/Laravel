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

Route::get('/', function () {
    return view('item');
});

//認証に必要なルーティング定義
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//コントローラーで利用するためnameメソッドをチェーン
Route::get('/item', 'ItemController@index')->name('items');
Route::get('/item/detail/{id}', 'ItemController@detail')->name('item');
//twitterLogin
Route::get('login/twitter', 'Auth\LoginController@redirectToTwitterProvider')->name('twitter_login');
Route::get('login/twitter/callback', 'Auth\LoginController@handleTwitterProviderCallback');
