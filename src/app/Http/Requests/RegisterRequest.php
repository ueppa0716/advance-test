<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users|string|max:191',
            'password' => 'required|min:8|max:191',
            'location' => 'required',
            'category' => 'required',
            'detail' => 'required',
            'photo' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'name.string' => '名前を文字列で入力してください',
            'name.max' => '名前を191文字以下で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'email.unique' => 'メールアドレスは既に使用されています',
            'name.string' => 'メールアドレスを文字列で入力してください',
            'name.max' => 'メールアドレスを191文字以下で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードを8文字以上で入力してください',
            'password.max' => 'パスワードを191文字以下で入力してください',
            'location.required' => 'エリアを選択してください',
            'category.required' => 'ジャンルを選択してください',
            'detail.required' => '店舗紹介を入力してください',
            'photo.required' => '写真を選択してください',
        ];
    }
}
