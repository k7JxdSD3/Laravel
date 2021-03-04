<?php

namespace App\Http\Controllers;

use App\Model\Address;
use App\Model\Cart;
use App\Model\Payment;
use App\Model\Purchase;
use App\User;
use Illuminate\Http\Request;
use Auth;

class PaymentController extends Controller
{
	public function __construct(Address $address, Cart $cart, Payment $payment, Purchase $purchase)
	{
		$this->middleware('auth:user');
		$this->address = $address;
		$this->cart = $cart;
		$this->payment = $payment;
		$this->purchase = $purchase;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$user = Auth::user();
		//DBから選択したお届け先住所を取得
		$address = $this->address->findGet($user->address_id);
		if (!$address) {
			$error = '住所を選択してください';
			return redirect()->route('address')->with('error', $error);
		}
		//現在のカートをすべて取得
		$carts = $this->cart->getAllCarts();
		//collectionから小計の合計を取得
		$carts_collection = collect($carts);
		$total = $carts_collection->sum('subtotal');
		$including_tax = round($total + $total * 0.1);

		//デフォルトのクレジットカードIDを取得
		$customer = User::getCustomer($user->stripe_id);
		$default_card = User::getCard($customer->default_source);

		return view('payments.create', compact('address', 'carts', 'total', 'including_tax', 'default_card'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$user = Auth::user();
		//DBから選択したお届け先住所を取得
		$address = $this->address->findGet($user->address_id);
		if (!$address) {
			$error = '住所を選択してください';
			return redirect()->route('address')->with('error', $error);
		}
		//現在のカートをすべて取得
		$carts = $this->cart->getAllCarts();
		if (empty($carts)) {
			$error = 'カートに商品が存在しません';
			return redirect()->route('items')->with('error', $error);
		}
		//collectionから小計の合計を取得
		$carts_collection = collect($carts);
		$total = $carts_collection->sum('subtotal');
		$including_tax = round($total + $total * 0.1);

		if ($including_tax < 50) {
			$error = '合計金額が￥50以上から注文ができます';
			return redirect()->route('payments.create')->with('error', $error);
		}

		$payment = $this->payment->createCharge($address, $carts, $including_tax);
		return redirect()->route('payments.complete', ['payment_id' => $payment->id]);
	}

	public function complete($payment_id)
	{
		$charge_id = $this->payment->find($payment_id)->value('charge_id');
		return view('payments.complete', compact('charge_id'));
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Payment  $payment
	 * @return \Illuminate\Http\Response
	 */
	public function show(Payment $payment)
	{
		return view('payments.show', ['payment' => $payment]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Payment  $payment
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Payment $payment)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Payment  $payment
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Payment $payment)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Payment  $payment
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Payment $payment)
	{
		//
	}

	public function getStripeId() {
		$user = Auth::user();
		$check_card = $this->checkCard($user);
		return view('payment.index');
	}
}
