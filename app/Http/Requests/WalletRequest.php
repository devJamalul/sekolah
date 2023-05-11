<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WalletRequest extends FormRequest
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

    public function attributes()
    {
        return [
            'name' => 'nama dompet',
            'init_value' => 'saldo awal'
        ];
    }

    public function postMethod(): array
    {
        return [
            'name'      => [
                'required',
                Rule::unique('wallets')->where(function ($q) {
                    $q->where('name', $this->name);
                    $q->where('school_id', session('school_id'));
                    $q->whereNull('deleted_at');
                })
            ],
            'init_value'    => 'required|min:0',
        ];

    }

    public function putMethod(): array
    {
        return [
            'name'      => [
                'required',
                Rule::unique('wallets')->where(function ($q) {
                    $q->where('name', $this->name);
                    $q->where('school_id', session('school_id'));
                    $q->whereNull('deleted_at');
                })->ignore($this->wallet->id, 'id')
            ],
            'init_value'    => 'required|min:0',
        ];
    }
}
