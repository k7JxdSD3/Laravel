<?php

namespace App\Http\Controllers;

use App\Model\Address;
use App\Model\Card;
use App\Model\Cart;
use App\Model\Payment;
use App\Model\Purchase;
use App\User;
use Auth;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
	public function __construct(Address $address, Card $card, Cart $cart, Payment $payment, Purchase $purchase)
	{
		$this->middleware('auth:user');
		$this->address = $address;
		$this->card = $card;
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
		$payments = $this->payment->getAllPayments();
		return view('payments.index', compact('payments'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$payment = $this->payment->getPayment($id);
		if (!$payment) {
			return redirect()->back();
		}
		return view('payments.show', compact('payment'));
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

		$address = null; 
		if (isset($user->address_id)) {
			$address = $this->address->findGet($user->address_id);
		}

		$default_card = null;
		if (isset($user->stripe_id)) {
			//デフォルトのクレジットカードIDを取得
			$customer = $this->card->getCustomer($user->stripe_id);
			$default_card = $this->card->getCard($customer->default_source);
		}

		//現在のカートをすべて取得
		$carts = $this->cart->getAllCarts();
		//collectionから小計の合計を取得
		$carts_collection = collect($carts);
		$total = $carts_collection->sum('subtotal');
		$including_tax = round($total + $total * 0.1);

		return view('payments.create', compact('address', 'carts', 'total', 'including_tax', 'default_card'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{
		$user = Auth::user();
		//DBから選択したお届け先住所を取得
		if (!isset($user->address_id)) {
			$error = '住所を選択してください';
			return redirect()->route('address')->with('error', $error);
		}
		$address = $this->address->findGet($user->address_id);

		if (!isset($user->stripe_id)) {
			$error = 'クレジットカードを登録してください';
			return redirect()->route('cards.create')->with('error', $error);
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

		$payment = $this->payment->createCharge($address, $carts, $including_tax);
		if (!$payment) {
			$error = '注文に失敗しました。クレジットカード又は注文をご確認ください。';
			return redirect()->route('payments.create')->with('error', $error);
		}
		//注文完了のメール送信
		$this->payment->sentEmailComplete($payment->id);

		return redirect()->route('payments.complete', ['payment_id' => $payment->id]);
	}

	public function complete($payment_id)
	{
		$payment = $this->payment->where('user_id', Auth::user()->id)->find($payment_id);
		if (!isset($payment)) {
			$error = '無効な操作です';
			return redirect()->route('items')->with('error', $error);
		}
		$charge_id = $payment->charge_id;
		return view('payments.complete', compact('charge_id'));
	}

	/*
	 * $payment->status
	 * 0 手配中
	 * 1 配送中
	 * 2 配送済み
	 * 3 キャンセル
	 * */
	public function cancel($id) {
		$payment = $this->payment->getPayment($id);
		if ($payment->status !== 0) {
			$error = '無効な操作です';
			return redirect()->back()->with('error', $error);
		}
		//キャンセル処理
		$result = $this->payment->cancelCharge($payment);
		if (!$result) {
			session()->flash('error', 'ご注文のキャンセルに失敗しました');
			return redirect()->route('items');
		}
		session()->flash('success', 'ご注文をキャンセルしました');
		return redirect()->route('items');
	}
}
