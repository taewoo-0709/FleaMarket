<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = Auth::user();
        $hasShipping = session()->has('shipping');

        $postcodeRule = ($hasShipping || $user->postcode) ? 'nullable|string|size:8' : 'required|string|size:8';
        $addressRule = ($hasShipping || $user->address) ? 'nullable|string|max:255' : 'required|string|max:255';

        return [
            'payment_id' => 'required|exists:payments,id',
            'postcode' => $postcodeRule,
            'address' => $addressRule,
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'payment_id.required' => '支払い方法を選択してください',
            'address.required' => '配送先住所を選択してください',
        ];
    }
}
