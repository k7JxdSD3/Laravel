<?php
namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Socialite;

class SnsBaseController extends Controller
{
    //public function __construct()
    //{
    //    $this->middleware('auth:user');
    //}

	//ログインしたらSocialiteのドライバーにリダイレクト
	public function getAuth($provider) {
		return Socialite::driver($provider)->redirect();
	}

	//戻ってくるエンドポイント
	public function authCallback($provider) {
		//User情報の取得
		try {
			$user_info = $this->getProviderUserInfo($provider);
		} catch (Exception $exception) {
			return redirect('/');
		}
		//画像の保存
		$original_url = str_replace('_nomal.', '.', $user_info->avatar);
		//画像をリネイムして保存
		$profile_image_path = $this->_putProfileImage($user_info->id, $original_url);

		//socialite_idがなければDB挿入
		$user = User::updateOrCreate(['socialite_id' => $user_info->id], [
			'name' => $user_info->name,
			'socialite_id' => $user_info->id,
			'access_token' => $user_info->token,
			'access_token_secret' => $user_info->tokenSecret,
			'avatar' => $profile_image_path,
			'profile' => $user_info->user['description'],
		]);
		//ログインの為user情報取得
		$user = User::where('socialite_id', $user_info->id)->first();

		Auth::login($user);
		session()->flash('flash_message', 'Socialiteログインに成功しました');
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

	public function logout(Request $request) {
		Auth::logout();
		return redirect('/');
	}



	//画像をディレクトリに保存
	private function _putProfileImage($user_id, $photo_url) {
		//$unique_name = sha1_file(uniqid(mt_rand(), true));
		$image = file_get_contents($photo_url);
		$extention = $this->_getImageType($photo_url);
		//$profile_image_path = sprintf('%s%s%s', 'profile_image/', $user_id, image_type_to_extension($extention));

		$profile_image_path = 'profile_image/' . $user_id . image_type_to_extension($extention);
		if (File::exists($profile_image_path)) {
			Storage::delete($profile_image_path);
		}
		//storage/app に保存
		Storage::disk('local')->put($profile_image_path, $image);
		return $profile_image_path;

		//$unique_file = sprintf('%s%s', $unique_name, image_type_to_extension($extention)));

		//$temp_path = $user_info->avatar->store('public/temp');
		//$read_temp_path = str_replace('public/', 'storage/', $avatar);
		//$avatar_name = str_replace('public/temp/', '', $temp_path);
		//$storage_path = 'public/socialite_image/' . $avatar_name;

		//Storage::move($temp_path, $storage_path);
		//$read_path = str_replace('public/', 'storage/', $storage_path);
	}

	//拡張子確認
	private function _getImageType($image_url) {
	//画像の拡張子判定
		list($img_with, $img_height, $mime_type, $attr) = getimagesize($image_url);
		switch ($mime_type) {
			case IMAGETYPE_JPEG:
				$extention = 'jpg';
				break;
			case IMAGETYPE_PNG:
				$extention = 'png';
				break;
			case IMAGETYPE_GIF:
				$extention = 'gif';
				break;
		}
		return $extention;
	}

}

