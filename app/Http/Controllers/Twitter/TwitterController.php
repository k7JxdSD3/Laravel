<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Abraham\TwitterOAth\TwitterOAth;

class TwitterController extends Controller
{
	public function search() {
		return view('twitter.search');
	}

	public function result(Request $request) {
		//検索キーワード格納
		$keyword = $request->keyword;

		//TwitterAPIから情報を取得
		$rule = [
			'q' => $keyword,
			'count' => '5',
			'result_type' => 'recent'
		];
		$search_result = \Twitter::get('search/tweets', $rule)->statuses;
		return view('twitter.result', ['search_result' => $search_result]);
	}
}
