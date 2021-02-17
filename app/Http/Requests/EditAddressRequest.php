<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditAddressRequest extends FormRequest
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
			'zip' => ['required', 'numeric', 'digits:7'],
			'prefectures' => ['required', 'string', 'jp_prefecture'],
			'city' => ['required', 'string', 'max:24'],
			'address' => ['required', 'string', 'max:255'],
			'phone_number' => ['required', 'numeric', 'phone_number'],
        ];
    }
}
