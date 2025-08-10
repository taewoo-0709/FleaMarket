<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'shipping_postcode' => 'required|string|size:8',
            'shipping_address' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'shipping_postcode.required' => '郵便番号を入力してください',
            'shipping_postcode.size' => '郵便番号は8桁で入力してください',
            'shipping_address.required' => '住所を入力してください',
            'shipping_address.max' => '255文字以内で入力してください',
        ];
    }
}