<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Card extends Model
{
	//新規の人はカード情報を入力して得たtokenを使用し
	//stripeに顧客情報を登録、返ってきたidをstripe_idとしてUserに保存
	public function setCustomer($token) {
		$user = Auth::user();
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		//stripe_idが存在すればその顧客に登録
		try {
			$customer = \Stripe\Customer::create([
				'card' => $token,
				'name' => $user->name,
				'email' => $user->email,
				'description' => $user->id,
			]);
		} catch (\Stripe\Exception\CardException $e) {
			return false;
		}
		$stripe_id = $customer->id;
		if (!empty($stripe_id)) {
			$user = User::find(Auth::id());
			$user->stripe_id = $customer->id;
			$user->update();
			return true;
		}
		return false;
	}

	public function getCustomer($stripe_id) {
		$user = Auth::user();
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		$customer = null;
		if (!is_null($user->stripe_id)) {
			$customer = \Stripe\Customer::retrieve($stripe_id, []);
		}
		return $customer;
	}

	public function addCard($stripe_id, $token) {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		try {
			\Stripe\Customer::createSource(
				$stripe_id,
				['source' => $token,]
			);
			return true;
		} catch (\Stripe\Exception\InvalidRequestException $e) {
			// Invalid parameters were supplied to Stripe's API
			//不正なstripe_idの場合nullに変更する
			$user = User::findOrFail(Auth::id());
			$user->stripe_id = null;
			$user->save();
			return false;
		}
		return false;
	}

	public function getCards() {
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

	public function getCard($card_id) {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		$user = Auth::user();
		$card = null;
		if (!is_null($user->stripe_id)) {
			$card = \Stripe\Customer::retrieveSource($user->stripe_id, $card_id);
		}
		return $card;
	}

	public function changeDefaultCard($card_id) {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		$user = Auth::user();
		if ($user->stripe_id) {
			\Stripe\Customer::update($user->stripe_id, ['default_source' => $card_id]);
			return true;
		}
		return false;
	}

	public function deleteCard($card_id) {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		$user = Auth::user();
		if ($user->stripe_id) {
			\Stripe\Customer::deleteSource(
				$user->stripe_id,
				$card_id
			);
			return true;
		}
		return false;
	}
}
