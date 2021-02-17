<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\ResetEmailController;
use App\Http\Requests\EditUserRequest;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function __construct(User $user, ResetEmailController $email_reset) {
		$this->middleware('auth:user');
		$this->user = $user;
		$this->email_reset = $email_reset;
	}

	//ユーザーの編集画面
	public function showEditForm() {
		$user = $this->user->findGet();
		return view('auth.edit', compact('user'));
	}

	public function edit(EditUserRequest $request) {
		 $update_email = $this->user->editDb($request->all());
		 //メールアドレスがそのままの場合はメールのメッセージはなし
		 if ($update_email) {
		 	//メールアドレスが既に存在している場合はメッセージだけ出力
			if ($update_email === true) {
				//メールアドレスを変更した場合確認メールの送信
				$this->email_reset->sendEmailResetLink($request->input('email'));
			}
		session()->flash('warning',
			'ご記入頂いたメールアドレスに確認メールを送信しました。メールを確認してください。※まだメースアドレスの更新は行われていません。'
		);
		}
		return redirect()->route('auth.edit')->with('status', 'ユーザー情報を編集しました');
	}

}
