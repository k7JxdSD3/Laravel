<?php

namespace App\Http\Controllers;

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

	public function index()
	{
		$user_id = Auth::user()->id;
		//データベースからカート情報を取得
		$carts = Cart::where('user_id', $user_id)->get();
		$carts_data = [];

		foreach ($carts as $cart) {
			$carts_data[] = [
				//cartsテーブル
				'number_items' => $cart->number_items,
				//itemsテーブル
				'name' => $cart->item->name,
				'price' => $cart->item->price,
				'item_id' => $cart->item->id,
				//小計
				'subtotal' => $cart->number_items * $cart->item->price,
			];
		}

		//collectionから小計の合計を取得
		$collection = collect($carts_data);
		$total = $collection->sum('subtotal');
		$including_tax= round($total + $total * 0.1);
		return view('cart.index', compact('carts_data', 'total', 'including_tax'));
	}

	public function cartDelete($item_id)
	{
		$user_id = Auth::user()->id;

		//カートの商品数
		if (!$number_items = Cart::where('user_id', $user_id)->where('item_id', $item_id)->value('number_items')) {
			return back();
		}

		//商品の在庫数
		$item_stock = Item::where('id', $item_id)->value('stock');

		return DB::transaction(function() use($item_id, $user_id, $item_stock, $number_items) {
			//カート商品削除
			Cart::where([['item_id', $item_id], ['user_id', $user_id]])->delete();
			session()->flash('flash_message', '商品を削除しました');

			//在庫から削除分をたす
			$item = Item::find($item_id);
			$item->stock = $item_stock + $number_items;
			$item->save();
			return redirect()->route('cart');
		});
	}

	public function cartAdd($item_id)
	{
		//商品が存在するか確認
		if (!$item_stock = Item::where('id', $item_id)->value('stock')) {
			return back();
		}

		$user_id = Auth::user()->id;
		//既存のカート内の商品数取得
		if (!$number_items = Cart::where([['user_id', $user_id], ['item_id', $item_id]])->value('number_items')) {
			$number_items = 0;
		}

		return DB::transaction(function() use($item_id, $user_id, $number_items, $item_stock) {
			//商品がcartsテーブルにあれば更新なければ追加
			Cart::updateOrCreate(
				['item_id' => $item_id, 'user_id' => $user_id],
				['number_items' => $number_items + 1]
			);
			session()->flash('flash_message', '商品を追加しました');

			//在庫から追加分を引く
			$item = Item::find($item_id);
			$item->stock = $item_stock - 1;
			$item->save();
			return redirect()->route('cart');
		});
	}

}
