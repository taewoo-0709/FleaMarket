<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string',
            'item_explain' => 'required|string|max:255',
            'image_url' => 'required|image|mimes:jpeg,png',
            'category_id' => 'required|exists:categories,id',
            'condition_id' => 'required|exists:conditions,id',
            'price' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '商品名を入力してください',
            'item_explain.required' => '商品説明を入力してください',
            'item_explain.max' => '255文字以内で入力してください',
            'image_url.required' => '画像を選択してください',
            'image_url.mimes' => 'ファイルデータは.jpegか.pngを選択してください',
            'category_id.required' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品状態を選択してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は数字で入力してください(小数点は使用できません)',
            'price.min' => '価格は0円以上で入力してください',
        ];
    }
}
