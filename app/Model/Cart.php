<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Cart extends Model
{
	public function item()
	{
		return $this->belongsTo('App\Model\Item');
	}

	//論理削除に変更
	use SoftDeletes;
	protected $dates = ['daleted_at'];

	protected $fillable = ['user_id', 'item_id', 'number_items'];

	public function getAllCarts() {
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
				'item_stock' => $cart->item->stock,
				//小計
				'subtotal' => $cart->number_items * $cart->item->price,
			];
		}
		return  $carts_data;
	}

}

