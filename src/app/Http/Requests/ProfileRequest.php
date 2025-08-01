<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'avatar' => 'nullable|image|mimes:jpeg,png',
            'name' => 'required|string|max:20',
            'postcode' => 'required|string|size:8',
            'address' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' => 'ファイルデータは.jpegか.pngを選択してください',
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => '20字以内で入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.size' => '郵便番号はハイフンを含む8文字で入力してください',
            'address.required' => '住所を入力してください',
            'address.max' => '住所は255文字以内で入力してください',
        ];
    }
}
