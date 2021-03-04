<?php

namespace App\Http\Controllers\Admin;

//Controllerの呼び出し
use App\Http\Controllers\Controller;
use App\Http\Requests\AddItemRequest;
use Illuminate\Http\Request;
//Itemモデルを呼び出す
use App\Model\Item;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
//バリデーションを呼び出す
use Validator;
//画像リサイズ
use InterventionImage;

class ItemController extends Controller {

	//adminの認証
	public function __construct(Item $item) {
		$this->middleware('auth:admin');
		$this->item = $item;
	}

	public function index() {
		//データベースからすべての情報を取得
		$items = $this->item->paginate(14);
		return view('admin.item.index', compact('items'));
	}

	public function detail($id) {
		$item = $this->item->where('id', $id)->first();
		if (isset($item)) {
			return view('admin.item.detail', compact('item'));
		} else {
			//DBに値が存在しない場合は前のページに戻す
			return back();
		}
	}

	public function showAddForm() {
		return view('admin.item.add');
	}

	public function add(AddItemRequest $request) {
		$request_params = $request->all();
		//画像をディレクトリに保存
		if ($request->image_name) {
			$request_params['image_name'] = $this->_putImage($request->file('image_name'));
			if ($request_params['image_name'] === false) {
				$error = '同一画像が存在します他の画像を試してください';
				return redirect()->route('admin.item.add')->with('error', $error)->withInput($request->except('image_name'));
			}
		}
		//データ挿入
		if ($this->item->addItem($request_params)) {
			session()->flash('success', '商品を追加いたしました');
		} else {
			session()->flash('error', '商品を追加できませんでした');
		}
		return redirect()->route('admin.items');
	}

	public function showEditForm($id) {
		$item = $this->item->where('id', $id)->first();
		if (isset($item)) {
			return view('admin.item.edit', compact('item'));
		} else {
			//DBに値が存在しない場合は前のページに戻す
			return back();
		}
	}

	public function edit(AddItemRequest $request, $id = null) {
		$request_params = $request->all();
		if ($request->image_name && $request->delete !== 'delete') {
			$request_params['image_name'] = $this->_putImage($request->file('image_name'), $id);
			//同一画像に更新した場合
			if ($request_params['image_name'] === false) {
				$error = '同一画像が存在します。他の画像を試してください';
				return redirect()->route('admin.item.edit', ['id' => $id])->with('error', $error);
			}
		}
		if ($request->delete === 'delete') {
			$request_params['image_name'] = $this->_deleteImage($id);
		}
		if ($this->item->editItem($request_params, $id)) {
			session()->flash('success', '商品を編集いたしました');
		} else {
			session()->flash('error', '商品を編集できませんでした');
		}
		return redirect()->route('admin.item', ['id' => $id]);
	}

	/*
	 *----------------------------------------------------------------------------
	 * 画像処理
	 *----------------------------------------------------------------------------
	 */

	//拡張子確認
	private function _getImageType($image_url) {
		//画像の拡張子判定
		list($img_with, $img_height, $mime_type, $attr) = getimagesize($image_url);
		switch ($mime_type) {
			case IMAGETYPE_JPEG:
				$extention = 'jpg';
				break;
			case IMAGETYPE_PNG:
				$extention = 'png';
				break;
			case IMAGETYPE_GIF:
				$extention = 'gif';
				break;
		}
		return $extention;
	}

	//画像をディレクトリに保存
	private function _putImage($image_url, $id = null) {
		$extention = $this->_getImageType($image_url);
		$unique_name = sha1_file($image_url);
		$image_name = sprintf('%s.%s', $unique_name, $extention);
		$image_path = 'public/item_image/';
		$before_image_name = $this->item->where('id', $id)->value('image_name');
		$before_image_count = $this->item->where('image_name', $image_name)->count();

		//同一画像のチェック
		if ($before_image_count) {
			return false;
		}
		if ($before_image_name) {
			Storage::delete($image_path . $before_image_name);
		}

		//storage/app に保存
		$image = $image_url->storeAs('public/item_image', $image_name);

		//リサイズ
		//取得する画像を編集
		$path = public_path('storage/item_image/' . $image_name);
		InterventionImage::make($path)
			->resize(100, 100, function ($constration) {
				$constration->aspectRatio();
			})->save($path);
		return $image_name;
	}

	//画像の削除
	public function _deleteImage($id) {
		$image_name = $this->item->where('id', $id)->value('image_name');
		$image_path = 'public/item_image/' . $image_name;
		if (File::exists(storage_path('app/' . $image_path))) {
			Storage::delete($image_path);
		}
		return null;
	}
}
