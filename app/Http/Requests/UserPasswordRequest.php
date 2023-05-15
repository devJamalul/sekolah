<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'old_password' => 'required|current_password:web',
            'password' => 'required|confirmed|string|min:8',
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => 'password lama',
            'password_confirmation' => 'konfirmasi password baru'
        ];
    }
}
