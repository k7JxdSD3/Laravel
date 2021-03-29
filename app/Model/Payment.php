<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use App\User;
use App\Model\Cart;
use App\Model\Purchase;
use App\Notifications\PaymentCompleteNotification;
use Auth;

class Payment extends Model
{
	protected $fillable = [
		'user_id',
		'description',
		'amount',
		'charge_id',
		'name',
		'zip',
		'prefectures',
		'city',
		'address',
		'phone_number',
	];

	public function purchases()
	{
		return $this->hasMany('App\Model\Purchase');
	}

	public function getAllPayments()
	{
		$payments = $this->where('user_id', Auth::id())->get();
		return $payments;
	}

	public function getPayment($id = null)
	{
		$payment = $this->where('user_id', Auth::id())->with('purchases')->find($id);
		return $payment;
	}

	public function createCharge($address, $carts, $including_tax)
	{
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		$user = Auth::user();
		if (!isset($user->stripe_id)) {
			return false;
		}
		$charge_id = null;
		$description = '商品購入';

		//オーソリ
		$charge = \Stripe\Charge::create([
			'amount' => $including_tax,
			'currency' => 'jpy',
			'description' => $description,
			'customer' => $user->stripe_id,
		]);
		$charge_id = $charge['id'];

		try {
			//データベースの更新処理
			$payment = DB::transaction(function() use($user, $description, $charge, $address, $carts, $including_tax) {
				//支払い情報
				//配送状況追加
				$payment = new Payment;
				$payment->user_id = $user->id;
				$payment->description = $description;
				$payment->amount = $including_tax;
				$payment->charge_id = $charge['id'];
				$payment->name = $address->name;
				$payment->zip = $address->zip;
				$payment->prefectures = $address->prefectures;
				$payment->city = $address->city;
				$payment->address = $address->address;
				$payment->phone_number = $address->phone_number;
				$payment->save();

				//購入履歴
				//foreachで商品ぶん回してデータベースへ保存
				foreach ($carts as $cart) {
					$purchase = new Purchase;
					$purchase->item_id = $cart['item_id'];
					$purchase->item_name = $cart['name'];
					$purchase->price = $cart['price'];
					$purchase->number_items = $cart['number_items'];
					$purchase->payment_id = $payment->id;
					$purchase->save();
				}

				//カートの削除
				Cart::where('user_id', $user->id)->delete();
				return $payment;
			});

			return $payment;

		} catch (\Exception $e) {
			// Something else happened, completely unrelated to Stripe
			if ($charge_id !== null) {
				//オーソリの取り消し
				\Stripe\Refund::create([
					'charge' => $charge_id,
				]);
			}
			return false;
		}
	}

	public function sentEmailComplete($payment_id)
	{
		$user = User::find(Auth::user()->id);
		$user->notify(new PaymentCompleteNotification($payment_id));
		return true;
	}

	public function cancelCharge($payment) {
		\Stripe\Stripe::setApiKey(\Config::get('payment.stripe_secret_key'));
		//オーソリの取り消し
		\Stripe\Refund::create([
			'charge' => $payment->charge_id,
		]);

		try {
			//statusの変更
			//$payment->status 3 キャンセル
			$payment->status = 3;
			$save = $payment->save();

			$purchases = Purchase::where('payment_id', $payment->id)->get();
			//キャンセル分を在庫に戻す
			foreach ($purchases as $purchase) {
				$item = Item::find($purchase->item_id);
				$item->increment('stock', $purchase->number_items);
			}
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}
