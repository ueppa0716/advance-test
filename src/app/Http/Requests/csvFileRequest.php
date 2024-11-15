<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class csvFileRequest extends FormRequest
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
            'csvFile' => 'required|file|mimes:csv,txt',
        ];
    }

    public function messages()
    {
        return [
            'csvFile.required' => 'CSVファイルを選択してください。',
            'csvFile.file' => 'アップロードされたファイルが不正です。',
            'csvFile.mimes' => 'CSV形式のファイルをアップロードしてください。',
        ];
    }
}
