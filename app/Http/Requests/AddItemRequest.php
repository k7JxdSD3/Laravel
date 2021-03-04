<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddItemRequest extends FormRequest
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
			'name' => ['required', 'string', 'max:255'],
			'explanation' => ['required', 'string', 'max:255'],
			'price' => ['required', 'numeric', 'max:4294967295', 'min:0', 'sometimes'],
			'stock' => ['required', 'numeric', 'max:4294967295', 'min:0'],
			'image_name' => ['image', 'mimes:jpeg,jpg,png,gif', 'max:2048', 'nullable'],
			'delete' => ['nullable', 'sometimes'],
		];
	}
}
