<?php

namespace App\Http\Controllers;

use App\Model\Address;
use App\Http\Requests\AddAddressRequest;

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
		$addresses = $this->address->all_get();
		return view('address.index', compact('addresses'));
	}

	// 登録フォーム
	public function showAddForm() {
		return view('address.add');
	}

	// 登録処理
	//リクエストでバリデーション
	public function add(AddAddressRequest $request) {
		$this->address->add_db($request->all());
		session()->flash('success', 'お届け先を登録しました');
		return redirect()->route('address');
	}

	// 編集フォーム
	public function showEditForm($address_id) {
		if (!$address = $this->address->find_get($address_id)) {
			session()->flash('error', '無効な操作です');
			return redirect()->route('address');
		}
		return view('address.edit', compact('address'));
	}

	// 編集処理
	//リクエストでバリデーション
	public function edit(AddAddressRequest $request, $address_id) {
		if ($this->address->edit_db($request->all(), $address_id)) {
			session()->flash('success', 'お届け先を編集しました');
		} else {
			session()->flash('error', 'お届け先を編集できませんでした');
		}
		return redirect()->route('address');
	}

	// 削除処理
	public function delete($address_id) {
		if ($this->address->soft_delete_db($address_id)) {
			session()->flash('success', 'お届け先を削除しました');
		} else {
			session()->flash('error', 'お届け先を削除できませんでした');
		}
		return redirect()->route('address');
	}
}

