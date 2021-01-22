<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

	public function showLoginForm()
	{
		return view('auth.login');
	}

	protected function guard()
	{
		return \Auth::guard('user');
	}

	public function logout(Request $request)
	{
		\Auth::guard('user')->logout();
		return redirect('/login');
	}

/*
|--------------------------------------------------------------------------
| twitter Login
|--------------------------------------------------------------------------
 */
	public function redirectToTwitterProvider() {
		return Socialite::driver('twitter')->redirect();
	}

	public function handleTwitterProviderCalback() {
		try {
			$user = Socialite::with('twitter')->user();
		} catch (\Exception $e) {
			// エラーならログイン画面へ転送
			return redirect('/login')->with('oauth_error', 'ログインに失敗しました');
		}
		//DBにデータ挿入
		//$myinfo = User::firstOrCreate(
		//	//入れるレコードの指定
		//	['token' => $user->token],
		//	//新しく入れる値
		//	['name' => $user->nickname, 'email' => $user->getEmail()]
		//);
		//Auth::login($myinfo);
		\Debugber::info($user);
		return redirect()->to('/');
	}
/*
|--------------------------------------------------------------------------
|
|--------------------------------------------------------------------------
 */
}
