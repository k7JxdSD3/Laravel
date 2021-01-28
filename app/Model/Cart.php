<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
	public function item()
	{
		return $this->belongsTo('App\Model\Item');
	}

	//論理削除に変更
	use SoftDeletes;
	protected $dates = ['daleted_at'];

	//public function user()
	//{
	//	return $this->belongsTo('App\Model\User');
	//}
}

