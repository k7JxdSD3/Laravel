<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Itemモデルを呼び出す
use App\Model\Item;
//ユーザー取得
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller {

	public function index() {
		$items = Item::all();
		return view('item.index', compact('items'));
	}

	public function detail($id) {
		$item = Item::where('id', $id)->first();
		\Debugbar::info($item);
		if (isset($item)) {
			return view('item.detail', compact('item'));
		} else {
			//DBに値が存在しない場合は前のページに戻す
			return back();
		}
	}
}
