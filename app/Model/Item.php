<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Item extends Model {
	use SoftDeletes;

	protected $dates = ['daleted_at'];

	protected $fillable = [
		'name',
		'explanation',
		'price',
		'stock',
		'image_name',
	];

	public function addItem($request) {
		$item = new Item;
		$item->fill($request)->save();
		if ($item->fill($request)->save()) {
			return true;
		}
		return false;
	}

	public function editItem($request, $id) {
		$item = $this->findOrFail($id);
		if ($item->fill($request)->save()) {
			return true;
		}
		return false;
	}
}
