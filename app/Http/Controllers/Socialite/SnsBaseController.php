<?php
namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Socialite;

class SnsBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

	//Socialiteのドライバーにリダイレクト
	public function getAuth($provider) {
		return Socialite::driver($provider)->redirect();
	}

	//twitterから戻ってくるエンドポイント
	public function authCallback($provider) {
		$user_id = Auth::user()->id;

		//twitterのUser情報の取得
		$user_info = $this->getProviderUserInfo($provider);
		$user = User::find($user_id);
		$user->twitter_id = $user_info->id;
		$user->access_token = $user_info->token;
		$user->access_token_secret = $user_info->tokenSecret;
		$user->avater = $user_info->avatar;
		$user->profile = $user_info->user['description'];
		$user->save();

		session()->flash('flash_message', 'twitterログインに成功しました');
		return redirect()->route('home');

	}

	public function getProviderUserInfo($provider) {
		switch ($provider) {
			case 'twitter':
				return Socialite::driver($provider)->user();
				break;
			default:
				return null;
				break;
		}
	}
}

