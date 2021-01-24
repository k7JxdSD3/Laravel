<?php

namespace App\Http\Controllers\Admin;

//Controllerの呼び出し
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//Itemモデルを呼び出す
use App\Model\Item;
//ユーザー取得
use Illuminate\Support\Facades\Auth;
//バリデーションを呼び出す
use Validator;

class ItemController extends Controller {

	//adminの認証
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

	public function index()
	{
		//データベースからすべての情報を取得
		$items = Item::all();
		return view('admin.item.index', compact('items'));
	}

	public function detail($id)
	{
		$item = Item::where('id', $id)->first();
		if (isset($item)) {
			return view('admin.item.detail', compact('item'));
		} else {
			//DBに値が存在しない場合は前のページに戻す
			return back();
		}
	}

	public function showAddForm ()
	{
		return view('admin.item.add');
	}

	public function add(Request $request)
	{
		//リクエストのバリデーション処理
		$this->validationAdd($request);

		//データ挿入
		$item = new Item;
		$item->name = $request->name;
		$item->explanation = $request->explanation;
		$item->price = $request->price;
		$item->stock = $request->stock;
		$item->save();
		//保存後リダイレクト
		return redirect()->route('admin.items');
	}

	protected function validationAdd(Request $request)
	{
		return Validator::make($request->all(), [
			'name' => ['required', 'string', 'max:255'],
			'explanation' => ['required', 'string', 'max:255'],
			'price' => ['required', 'numeric', 'digits_between:0,10', 'sometimes'],
			'stock' => ['required', 'numeric', 'digits_between:0,10'],
		])->validate();
	}

	public function showEditForm ($id)
	{
		$item = Item::where('id', $id)->first();
		if (isset($item)) {
			\Debugbar::info($item);
			return view('admin.item.edit', compact('item'));
		} else {
			//DBに値が存在しない場合は前のページに戻す
			return back();
		}
	}

	public function edit(Request $request, $id)
	{
		//リクエストのバリデーション処理
		$this->validationAdd($request);

		//データ更新
		$item = Item::find($id);
		if (isset($item)) {
			$item->name = $request->name;
			$item->explanation = $request->explanation;
			$item->stock = $request->stock;
			$item->save();
			//保存後リダイレクト
			return redirect()->route('admin.item', ['id' => $id]);
		} else {
			//DBに値が存在しない場合は前のページに戻す
			return back();
		}
	}

}
