<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
            'comment' => 'required|max:400',
            'point' => 'integer|required|min:1|max:5',
            'photo' => 'required|image|mimes:jpeg,png',
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'コメントを入力してください',
            'comment.max' => 'コメントを400文字以下で入力してください',
            'point.integer' => '評価は整数で入力してください',
            'point.min' => '評価は1以上の値を選択してください',
            'point.max' => '評価は5以下の値を選択してください',
            'point.required' => '評価を入力してください',
            'photo.required' => '写真を選択してください',
            'photo.image' => '写真は画像ファイルである必要があります。',
            'photo.mimes' => '写真はjpeg, png形式のファイルを選択してください。',
        ];
    }
}
