<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EditUserRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => ['required', 'string', 'max:50'],
			'email' => ['required', 'email'],
			'new_password' => ['min:6', 'confirmed', 'different:password', 'nullable'],
			'password' => ['required',
				function ($attribute, $value, $fail) {
					if (!(\Hash::check($value, \Auth::user()->password))) {
						return $fail('現在のパスワードを正しく入力して下さい。');
					}
				},
			],
		];
	}
}
