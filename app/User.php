<?php

namespace App;

use App\Notifications\PasswordResetNotification;
use App\Model\EmailReset;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
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

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function editDb($request) {
		$user = $this->findOrFail(Auth::id());
		$user->name = $request['name'];
		if (isset($request['new_password'])) {
			$user->password = bcrypt($request['new_password']);
		}
		$same_email = $this->where('email', $request['email'])->count();
		//現在のメールアドレス
		if ($user->email === $request['email']) {
			$update_email = '';
		//同一メールアドレスが存在した場合
		} elseif ($same_email) {
			$update_email = $same_email;
		//メールアドレスの変更があった場合、メールの送信
		} elseif ($user->email !== $request['email']) {
			$update_email = true;
		}
		$user->save();
		return $update_email;
	}

	public function sendPasswordResetNotification($token) {
		$this->notify(new PasswordResetNotification($token));
	}


	public function findGet() {
		$user = $this->findOrFail(Auth::id());
		return $user;
	}
}
