<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ReserveRequest extends FormRequest
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
        $now = Carbon::now()->format('Y-m-d H:i');
        $date = Carbon::now()->format('Y-m-d');

        if ($this->has('reserve')) {
            return [
                'date' => 'required|date|after:' . $date,
                'people' => 'required|min:1|max:10',
            ];
        }

        if ($this->has('update')) {
            return [
                'date' => 'required|date|after:' . $now,
                'people' => 'required|min:1|max:10'
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'date.required' => '日付を入力してください',
            'date.after' => '明日以降の日時を入力してください',
            'people.required' => '人数を入力してください',
            'people.min' => '人数を1人以上で入力してください',
            'people.max' => '人数を10人以下で入力してください',
        ];
    }
}
