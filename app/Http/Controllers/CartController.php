<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Itemモデルを呼び出す
use App\Model\Cart;
use App\Model\Item;
//ユーザー取得
use Illuminate\Support\Facades\Auth;
//ファサードのトランザクションを使用
use Illuminate\Support\Facades\DB;

class CartController extends Controller {
	//userの認証
	public function __construct()
	{
		$this->middleware('auth:user');
	}

	public function index($id)
	{
		//ログインユーザーidとGETパラメーターidが異なる場合リダイレクト
		if (Auth::user()->id != $id) {
			return redirect()->route('items');
		}

		//データベースからカート情報を取得
		$carts = Cart::where('user_id', $id)->selectRaw('item_id, SUM(number_items) as total_items')->groupBy('item_id')->get();
		$carts_data = [];
		foreach ($carts as $cart) {
			$carts_data[] = [
				//cartsテーブル
				'total_items' => $cart->total_items,
				//itemsテーブル
				'name' => $cart->item->name,
				'price' => $cart->item->price,
				'item_id' => $cart->item->id,
				//小計
				'subtotal' => $cart->total_items * $cart->item->price,
			];
		}
		//collectionから小計の合計を取得
		$collection = collect($carts_data);
		$total = $collection->sum('subtotal');
		return view('cart.index', compact('carts_data', 'total'));
	}

	public function cartDelete($item_id)
	{
		$user_id = Auth::user()->id;
		//カートの商品数
		$cart_items = Cart::where('user_id', $user_id)->selectRaw('item_id, SUM(number_items) as cart_items')->groupBy('item_id')->value('cart_items');
		//商品の在庫数
		if (!$item_stock = Item::where('id', $item_id)->value('stock')) {
			return back();
		}

		//トランザクション
		return DB::transaction(function() use($item_id, $user_id, $item_stock, $cart_items) {
			//在庫か削除分をたす
			$item = Item::find($item_id);
			$item->stock = $item_stock + $cart_items;
			$item->save();

			//カート商品削除
			Cart::where('item_id', $item_id)->where('user_id', $user_id)->delete();
			return redirect()->route('cart', ['id' => $user_id]);
		});
	}

	public function cartAdd($item_id)
	{
		//itemが存在するか確認
		if (!$item_stock = Item::where('id', $item_id)->value('stock')) {
			return back();
		}
		$user_id = Auth::user()->id;

		//トランザクション
		return DB::transaction(function() use($item_id, $user_id, $item_stock) {
			//商品をcartsテーブルに挿入
			$cart = new Cart;
			$cart->user_id = $user_id;
			$cart->item_id = $item_id;
			$cart->number_items = 1;
			$cart->save();

			//在庫から追加分を引く
			$item = Item::find($item_id);
			$item->stock = $item_stock - 1;
			$item->save();
			return redirect()->route('cart', ['id' => $user_id]);
		});
	}

}
