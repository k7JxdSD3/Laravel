<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\EmailReset;
use Illuminate\Http\Request;

class ResetEmailController extends Controller
{
	// userの認証
	public function __construct(EmailReset $email_reset) {
		$this->middleware('auth:user');
		$this->email_reset = $email_reset;
	}

	public function sendEmailResetLink($request) {
		$this->email_reset->sentEmailReset($request);
		return true;
	}

	public function reset($token) {
		$status = $this->email_reset->updateUserDeleteEmailReset($token);
		return redirect()->route('auth.edit')->with($status['status'], trans($status['message']));
	}
}
