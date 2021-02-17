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

//コントローラーで利用するためnameメソッドをチェーン
Route::get('/item', 'ItemController@index')->name('items');
Route::get('/item/detail/{id}', 'ItemController@detail')->name('item');

// どのルートにも一致しない場合にさせたい処理
Route::fallback(function() {
	return view('item');
});

/*
|-------------------------------------------------------------------------
|2) Userログイン後
|-------------------------------------------------------------------------
 */

Route::group(['middleware' => 'auth:user'], function() {
	Route::get('/home', 'HomeController@index')->name('home');
	//商品カート
	Route::get('/cart', 'CartController@index')->name('cart');
	//商品の削除
	Route::post('/cart/delete/{item_id}', 'CartController@cartDelete')->name('cart.delete');
	Route::get('/cart/delete/{item_id}', function() { return redirect('/item'); });
	//商品をカートへ追加
	Route::post('/cart/add/{item_id}', 'CartController@cartAdd')->name('cart.add');
	Route::get('/cart/add/{item_id}', function() { return redirect('/item'); });

	//お届け先住所
	Route::get('/address', 'AddressController@index')->name('address');
	//住所登録
	Route::get('/address/add', 'AddressController@showAddForm')->name('address.add');
	Route::post('/address/add', 'AddressController@add');
	//住所編集
	Route::get('/address/edit/{address_id}', 'AddressController@showEditForm')->name('address.edit');
	Route::post('/address/edit/{address_id}', 'AddressController@edit');
	//住所削除
	Route::get('/address/delete/{address_id}', 'AddressController@delete')->name('address.delete');

	//ユーザー編集
	Route::get('/user/edit', 'Auth\UserController@showEditForm')->name('auth.edit');
	Route::post('/user/edit', 'Auth\UserController@edit')->name('auth.update');
	//メール更新処理
	Route::get('/user/email/{token}', 'Auth\ResetEmailController@reset');

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

	//管理者側のitemのルート
	Route::get('/item', 'Admin\ItemController@index')->name('admin.items');

	//管理者側のitem詳細画面へのルート
	Route::get('/item/detail/{id}', 'Admin\ItemController@detail')->name('admin.item');
	Route::get('/item/detail/', function() { return redirect('/admin/item'); });

	//商品追加
	Route::get('/item/add', 'Admin\ItemController@showAddForm')->name('admin.item.add');
	Route::post('/item/add', 'Admin\ItemController@add');

	//商品編集
	Route::get('/item/edit/{id}', 'Admin\ItemController@showEditForm')->name('admin.item.edit');
	Route::post('/item/edit/{id}', 'Admin\ItemController@edit');
	Route::get('/item/edit/', function() { return redirect('/admin/item'); });

	//User一覧
	Route::resource('/users', 'Admin\UserController');
});

/*
|-------------------------------------------------------------------------
|) twitter Login
|-------------------------------------------------------------------------
 */

Route::get('sns/{provider}/login', 'Auth\SnsBaseController@getAuth')->name('sns.login');
Route::get('sns/{ptovider}/callback', 'Auth\SnsBaseController@authCallback');
Route::get('sns/{ptovider}/logout', 'Auth\SnsBaseController@logoutauthCallback');

/*
|-------------------------------------------------------------------------
|) TwitterOAth
|-------------------------------------------------------------------------
 */
Route::get('twitter/search', 'Twitter\TwitterController@search')->name('twitter.search');
Route::get('twitter/result', 'Twitter\TwitterController@result');
Route::post('twitter/result', 'Twitter\TwitterController@result')->name('twitter.result');
