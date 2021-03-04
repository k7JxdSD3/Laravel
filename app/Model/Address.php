<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Providers\ValidatorServiceProvider;

class Address extends Model
{
	// 論理削除に変更
	use SoftDeletes;

	protected $dates = ['daleted_at'];

	protected $tabel = 'addresses';

	protected $fillable = [
		'user_id',
		'name',
		'zip',
		'prefectures',
		'city',
		'address',
		'phone_number',
	];

	public function allGetAddresses() {
		$addresses = $this->where('user_id', Auth::id())->get();
		return $addresses;
	}

	public function allGetAddressesCount() {
		$addresses = $this->where('user_id', Auth::id())->count();
		return $addresses;
	}

	public function findGet($address_id) {
		$address = $this->findOrFail($address_id);
		if ($address->user_id == Auth::id()) {
			return $address;
		}
		return false;
	}

	public function findAddress($request) {
		$address = $this->where([
			['user_id', Auth::id()],
			['zip', $request['zip']],
			['prefectures', $request['prefectures']],
			['city', $request['city']],
			['address', $request['address']],
		])->count();
		if ($address) {
			return true;
		}
		return false;
	}

	public function addDb($request) {
		$address = new Address;
		$address->user_id = Auth::id();
		$address->fill($request)->save();
		return true;
	}

	public function editDb($request, $address_id) {
		$address = $this->findOrFail($address_id);
		if (!$address->user_id == Auth::id()) {
			return false;
		}
		$address = $address->fill($request);
		//変更があった場合、同一住所を検索
		if ($address->isDirty(['zip', 'prefectures', 'city', 'address'])) {
			//同一住所が在る場合ははじく
			if ($this->findAddress($request)) {
				return false;
			}
		}
		$address->save();
		return true;
	}

	public function softDeleteDb($address_id) {
		$address = $this->findOrFail($address_id);
		if ($address->user_id == Auth::id()) {
			$address->delete();
			return true;
		}
		return false;
	}

}

