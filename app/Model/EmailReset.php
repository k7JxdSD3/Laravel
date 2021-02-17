<?php

namespace App\Model;

use App\Notifications\EmailResetNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EmailReset extends Model
{
	use Notifiable;

	protected $fillable = [
		'user_id',
		'new_email',
		'token',
	];

	public function addEmailResetDb($email) {
		//未変更の削除
		$unupdate = EmailReset::where('user_id', Auth::id());
		if ($unupdate) {
			$unupdate->delete();
		}
		//トークン生成
		$token = hash_hmac('sha256', Str::random(40) . $email, config('app.key'));

		//トークンをDBに保存
		$email_reset = new EmailReset;
		$email_reset->user_id = Auth::id();
		$email_reset->token = $token;
		$email_reset->new_email = $email;
		$email_reset->save();
		return $email_reset;
	}

	public function sentEmailReset($request) {
		$email_reset = $this->addEmailResetDb($request);
		//メール送信処理
		$email_reset = EmailReset::find($email_reset->id);
		$email_reset->sendEmailResetNotification($email_reset->token);
		return true;
	}

	public function updateUserDeleteEmailReset($token) {
		$email_reset = $this->where('token', $token)->first();
		if ($email_reset) {
			//他のユーザーに同じメールアドレスがするとtrue
			$email_exist = User::where([
				['id', '!=', $email_reset->user_id],
				['email', $email_reset->new_email]
			])->exists();
		}

		if ($email_reset && !$this->tokenExpired($email_reset->created_at) && !$email_exist) {
			$user = User::find($email_reset->user_id);
			$user->email = $email_reset->new_email;
			//トランザクション
			DB::transaction(function () use($email_reset, $user) {
				//ユーザーのメールアドレスの更新
				$user->save();
				//email_resetsのレコードの削除
				$email_reset->delete();
			});
			$status['status'] = 'status';
			$status['message'] = 'email.update';
		} else {
			if ($email_reset) {
				$email_reset->delete();
			}
			$status['status'] = 'warning';
			$status['message'] = 'email.failed';
		}
		return $status;
	}

	//トークン確認
	public function tokenExpired($created_at) {
		//有効期限は30分
		$expires = 60 * 30;
		return Carbon::parse($created_at)->addSeconds($expires)->isPast();
	}

	//メールの送信
	public function sendEmailResetNotification($token) {
		$this->notify(new EmailResetNotification($token));
	}

	//デフォルトだとemailカラムを参照するので変更
	public function routeNotificationForMail() {
		return $this->new_email;
	}
}
