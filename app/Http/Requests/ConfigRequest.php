<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigRequest extends FormRequest
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

    public function postMethod(): array
    {
        return [
            'code' => 'required|unique:configs,code',
            'name'      => 'required'
        ];
    }

    public function putMethod(): array
    {

        return [
            'code' => 'required',
            'name'      =>'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'Kode',
            'name' => 'Nama',
        ];
    }
}
