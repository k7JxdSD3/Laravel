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

//認証に必要なルーティング定義
Auth::routes();

/*
|-------------------------------------------------------------------------
|1) User認証不要
|-------------------------------------------------------------------------
 */

//ホーム画面
Route::get('/', function () {
    return view('item');
})->name('home');

//Route::get('/home', 'HomeController@index')->name('home');

//コントローラーで利用するためnameメソッドをチェーン
Route::get('/item', 'ItemController@index')->name('items');
Route::get('/item/detail/{id}', 'ItemController@detail')->name('item');


/*
|-------------------------------------------------------------------------
|2) Userログイン後
|-------------------------------------------------------------------------
 */

Route::group(['middleware' => 'auth:user'], function() {
	Route::get('/home', 'HomeController@index')->name('home');
});

/*
|-------------------------------------------------------------------------
|3) Admin認証不要
|-------------------------------------------------------------------------
 */

Route::group(['prefix' => 'admin'], function() {
	Route::get('/', function() { return redirect('/admin/home'); });
	Route::get('/login', 'Admin\LoginController@showLoginForm')->name('admin.login');
	//Loginボタンを押したときのルート
	Route::post('/login', 'Admin\LoginController@login');
});

/*
|-------------------------------------------------------------------------
|4) Adminログイン後
|-------------------------------------------------------------------------
 */


Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {
	Route::post('/logout', 'Admin\LoginController@logout')->name('admin.logout');
	Route::get('/home', 'Admin\HomeController@index')->name('admin.home');
});

/*
|-------------------------------------------------------------------------
|) twitter Login
|-------------------------------------------------------------------------
 */
Route::get('login/twitter', 'Auth\LoginController@redirectToTwitterProvider')->name('twitter_login');
Route::get('login/twitter/callback', 'Auth\LoginController@handleTwitterProviderCallback');
