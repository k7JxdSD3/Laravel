<?php

namespace App\Http\Controllers;

use App\Model\Address;
use App\Http\Requests\AddAddressRequest;
use App\User;
use Illuminate\Http\Request;
use Auth;

class AddressController extends Controller
{
	// userの認証
	public function __construct(Address $address)
	{
		$this->middleware('auth:user');
		$this->address = $address;
	}

	// 一覧ページ
	public function index() {
		$default_address_id = Auth::user()->address_id;
		$addresses = $this->address->allGetAddresses();
		$addresses_count = $this->address->allGetAddressesCount();
		dd($addresses_count);
		return view('address.index', compact('addresses', 'default_address_id'));
	}

	// 登録フォーム
	public function showAddForm() {
		return view('address.add');
	}

	// 登録処理
	//リクエストでバリデーション
	public function add(AddAddressRequest $request) {
		//同一住所を確認
		if ($this->address->findAddress($request)) {
			session()->flash('error', 'その住所はすでに登録されています');
		} else {
			//DBに挿入
			$this->address->addDb($request->all());
			session()->flash('success', 'お届け先を登録しました');
		}
		return redirect()->route('address');
	}

	// 編集フォーム
	public function showEditForm($address_id) {
		if (!$address = $this->address->findGet($address_id)) {
			session()->flash('error', '無効な操作です');
			return redirect()->route('address');
		}
		return view('address.edit', compact('address'));
	}

	// 編集処理
	//リクエストでバリデーション
	public function edit(AddAddressRequest $request, $address_id) {
		if ($this->address->editDb($request->all(), $address_id)) {
			session()->flash('success', 'お届け先を編集しました');
		} else {
			session()->flash('error', '無効な操作又は同一住所が存在するため編集できませんでした');
		}
		return redirect()->route('address');
	}

	// 削除処理
	public function delete($address_id) {
		if ($this->address->softDeleteDb($address_id)) {
			$user = User::findOrFail(Auth::user()->id);
			if ($address_id == $user->address_id) {
				$next_id = null;
				if ($address = $this->address->where('user_id', $user->id)->first()) {
					$next_id = $address->value('id');
				}
				$user->address_id = $next_id;
				$user->save();
			}
			session()->flash('success', 'お届け先を削除しました');
		} else {
			session()->flash('error', 'お届け先を削除できませんでした');
		}
		return redirect()->route('address');
	}

	public static function addDefaultAddress(Request $request) {
		if ($user = User::findOrFail(Auth::user()->id)) {
			$user->address_id = $request->address_id;
			$user->save();
			return redirect()->route('payments.create');
		}
		return false;
	}
}

