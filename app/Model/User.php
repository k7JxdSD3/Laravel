<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Item;

class User extends Model
{
	public function items()
	{
		return $this->belongsToMany(
			'App\Model\item',
			'pivotTable(=carts)',
			'fereignPivotKey(=user_id)',
			'relatedPivotKey(=item_id)'
		);
	}
}
