<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
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
        return match ($this->method()) {
            'POST' => $this->postMethod(),
            'PUT'  => $this->putMethod()
        };
    }

    protected function postMethod(): array
    {
        return [
            'school_name' => 'required|string|max:200',
            'province'   => 'required|string|max:100',
            'city'  => 'required|string|max:100',
            'postal_code' => 'required|string|max:7',
            'address'   => 'required|string|max:100',
            'grade'  => 'required|string|max:100',
            'email' => 'required|string|max:200',
            'phone'   => 'required|string|max:15',
            'foundation_head_name'   => 'required|string|max:100',
            'foundation_head_tlpn'   => 'required|string|max:100',
            'foundation_head_email'   => 'required|email|max:100|unique:users,email',
            'name_pic'  => 'required|string|max:100',
            'email_pic' => 'required|string|max:200|unique:users,email',
        ];
    }

    protected function putMethod(): array
    {
        return [
            'school_name' => 'required|string|max:200',
            'province'   => 'required|string|max:100',
            'city'  => 'required|string|max:100',
            'postal_code' => 'required|string|max:7',
            'address'   => 'required|string|max:100',
            'grade'  => 'required|string|max:100',
            'email' => 'required|string|max:200',
            'phone'   => 'required|string|max:15',
        ];
    }
}
