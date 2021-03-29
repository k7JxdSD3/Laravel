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
	public function address() {
		return $this->hasOne('App\Model\Address');
	}

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
		'address_id',
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

	//新規の人はカード情報を入力して得たtokenを使用し
	//stripeに顧客情報を登録、返ってきたidをstripe_idとしてUserに保存
	public static function setCustomer($token) {
		$user = Auth::user();
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		//stripe_idが存在すればその顧客に登録
		$customer = \Stripe\Customer::create([
			'card' => $token,
			'name' => $user->name,
			'email' => $user->email,
			'description' => $user->id,
		]);
		$stripe_id = $customer->id;
		if (!empty($stripe_id)) {
			$user = User::find(Auth::id());
			$user->stripe_id = $customer->id;
			$user->update();
			return true;
		}
		return false;
	}

	//public static function getCustomer($stripe_id) {
	//	$user = Auth::user();
	//	\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
	//	$customer = null;
	//	if (!is_null($user->stripe_id)) {
	//		$customer = \Stripe\Customer::retrieve($stripe_id, []);
	//	}
	//	return $customer;
	//}

	public static function addCard($stripe_id, $token) {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		\Stripe\Customer::createSource(
			$stripe_id,
			['source' => $token,]
		);
		return true;
	}

	public static function getCards() {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		$user = Auth::user();
		$cards = null;
		if (!is_null($user->stripe_id)) {
			$cards = \Stripe\Customer::allSources(
			$user->stripe_id,
				[
					'object' => 'card',
					'limit' => 3,
				]
			);
		}
		return $cards;
	}

	public static function getCard($card_id) {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		$user = Auth::user();
		$card = null;
		if (!is_null($user->stripe_id)) {
			try {
				$card = \Stripe\Customer::retrieveSource($user->stripe_id, $card_id);
			} catch (\Stripe\Exception\InvalidRequestException $e) {
				// Invalid parameters were supplied to Stripe's API
				return false;
			}
		}
		return $card;
	}

	public static function changeDefaultCard($card_id) {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		$user = Auth::user();
		if ($user->stripe_id) {
			try {
				\Stripe\Customer::update($user->stripe_id, ['default_source' => $card_id]);
				return true;
			} catch (\Stripe\Exception\InvalidRequestException $e) {
				// Invalid parameters were supplied to Stripe's API
				return false;
			}
		}
		return false;
	}

	public static function deleteCard($card_id) {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		$user = Auth::user();
		if ($user->stripe_id) {
			try {
				\Stripe\Customer::deleteSource(
					$user->stripe_id,
					$card_id
				);
				return true;
			} catch (\Stripe\Exception\InvalidRequestException $e) {
				// Invalid parameters were supplied to Stripe's API
				return false;
			}
		}
		return false;
	}

}
