<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentTypeRequest extends FormRequest
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
            'name' => 'tipe pembayaran',
            'wallet_id' => 'wallet'
        ];
    }

    public function postMethod(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'wallet_id' => 'required|exists:wallets,id',
            'name'      => [
                'required',
                Rule::unique('payment_types')->where(function ($q) {
                    $q->where('name', $this->name);
                    $q->where('school_id', $this->school_id);
                })
            ],
        ];
    }

    public function putMethod(): array
    {

        return [
            'school_id' => 'required|exists:schools,id',
            'wallet_id' => 'required|exists:wallets,id',
            'name'      => [
                'required',
                Rule::unique('payment_types')->where(function ($q) {
                    $q->where('name', $this->name);
                    $q->where('school_id', $this->school_id);
                })->ignore($this->payment_type->id)
            ],
        ];
    }
}
