<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
            'location' => 'required',
            'category' => 'required',
            'detail' => 'required',
            'photo' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'location.required' => 'エリアを選択してください',
            'category.required' => 'ジャンルを選択してください',
            'detail.required' => '店舗紹介を入力してください',
            'photo.required' => '写真を選択してください',
        ];
    }
}
