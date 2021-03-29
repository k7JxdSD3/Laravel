<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Card;
use App\User;
use Auth;

class CardController extends Controller
{
	public function __construct(Card $card)
	{
		$this->middleware('auth:user');
		$this->card = $card;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = Auth::user();
		if (!isset($user->stripe_id)) {
			return redirect()->route('cards.create');
		}
		$cards = $this->card->getCards();
		$customer = $this->card->getCustomer($user->stripe_id);
		$default_card_id = $customer->default_source;
		$cards_count = count($cards);

		return view ('cards.index', compact('cards', 'default_card_id', 'cards_count'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$cards = $this->card->getCards();
		$cards_count = count($cards);
		if ($cards_count >= 3) {
				$error = 'クレジットカードは3枚までしか登録できません';
				return redirect()->route('cards.index')->with('error', $error);
		}
		return view('cards.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$cards = $this->card->getCards();
		$cards_count = count($cards);
		if ($cards_count >= 3) {
				$error = 'クレジットカードは3枚までしか登録できません';
				return redirect()->route('cards.index')->with('error', $error);
		}
		$token = $request->stripeToken;
		$user = Auth::user();

		if (!$token) {
			//stripe側からtokenが返ってこなかった場合
			$error = 'カード登録に失敗しました、入力した内容に間違いがないか再度ご確認をお願いいたします。';
			return redirect()->route('cards.create')->with('error', $error);
		}

		if (!$user->stripe_id) {
			//stripe側から返ってきたtokenをuserモデルに保存
			$result = $this->card->setCustomer($token);
		} else {
			//顧客IDが存在する場合
			$result = $this->card->addCard($user->stripe_id, $token);
		}
		//card error
		if (!$result) {
			$error = 'カード登録に失敗しました、入力した内容に間違いがないか再度ご確認をお願いいたします。';
			return redirect()->route('cards.create')->with('error', $error);
		}
		$success = 'カード情報の登録が完了しました';
		return redirect()->route('cards.index')->with('success', $success);
	}

	public function edit($card_id)
	{
		$card = $this->card->changeDefaultCard($card_id);
		if (!$card) {
			$error = 'デフォルトのクレジットカードの変更に失敗しました';
			return redirect()->route('payments.create')->with('error', $error);
		}
		$success = 'デフォルトのクレジットカードを変更しました';
		return redirect()->route('payments.create')->with('success', $success);
	}

	public function delete($card_id)
	{
		$result = $this->card->deleteCard($card_id);
		if (!$result) {
			$error = 'カード削除に失敗しました。再度お試しください';
			return redirect()->route('cards.index')->with('error', $error);
		}
		$success = '選択したクレジットカードを削除しました';
		return redirect()->route('cards.index')->with('success', $success);
	}
}
