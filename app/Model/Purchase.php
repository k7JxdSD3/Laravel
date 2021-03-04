<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	protected $fillable = [
		'item_id',
		'price',
		'number_items',
		'prefectures',
		'payment_id',
	];
}
