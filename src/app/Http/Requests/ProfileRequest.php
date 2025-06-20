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
            'name' => ['required'],
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            'profile_image' => ['nullable', 'file', 'mimes:jpeg,png'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前は必須です。',
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.regex' => '郵便番号はハイフン付きで「123-4567」の形式で入力してください。',
            'address.required' => '住所は必須です。',
            'profile_image.mimes' => 'プロフィール画像は「.jpeg」または「.png」形式でアップロードしてください。',
        ];
    }

}
