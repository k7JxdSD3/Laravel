<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller {
	public function index() {
		$var = 'You never fail until you stop trying.';
		//変数をviewへ渡す
		return view('item.index', ['var' => $var]);
	}
}
