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

	public function all_get() {
		$addresses = $this->where('user_id', Auth::id())->get();
		return $addresses;
	}

	public function find_get($address_id) {
		$address = $this->findOrFail($address_id);
		if ($address->user_id == Auth::id()) {
			return $address;
		}
		return false;
	}

	public function add_db($request) {
		$address = new Address;
		$address->user_id = Auth::id();
		$address->fill($request)->save();
		return true;
	}

	public function edit_db($request, $address_id) {
		$address = Address::findOrFail($address_id);
		if ($address->user_id == Auth::id()) {
			$address->fill($request)->save();
			return true;
		}
		return false;
	}

	public function soft_delete_db($address_id) {
		$address = $this->findOrFail($address_id);
		if ($address->user_id == Auth::id()) {
			$address->delete();
			return true;
		}
		return false;
	}

}

