<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Item;
use App\Model\Address;

class User extends Model
{

	protected $tabel = 'users';

	protected $fillable = [
		'name',
		'email',
		'password',
		'socialite_id',
		'access_token',
		'access_token_secret',
		'avatar',
		'profile',
	];

	public function items() {
		return $this->belongsToMany(
			'App\Model\item',
			'pivotTable(=carts)',
			'fereignPivotKey(=user_id)',
			'relatedPivotKey(=item_id)'
		);
	}

	public function addresses() {
		return $this->hasMany('App\Model\Address');
	}
}
