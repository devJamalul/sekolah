<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayInvoiceRequest extends FormRequest
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
            'wallet_id.*' => 'required|exists:wallets,id'
        ];
    }

    public function messages(): array
    {
        return [
            'wallet_id.*.required' => ':Attribute #:position harus diisi'
        ];
    }

    public function attributes(): array
    {
        return [
            'wallet_id.*' => 'metode pembayaran',
        ];
    }
}
