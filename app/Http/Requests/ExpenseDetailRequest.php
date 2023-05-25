<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseDetailRequest extends FormRequest
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
            'wallet_id' => 'required|exists:wallets,id',
            'item_name' => 'required',
            'quantity' => 'required|min:0',
            'price' => 'required|min:0',
        ];
    }

    public function putMethod(): array
    {
        return [
            'wallet_id' => 'required|exists:wallets,id',
            'item_name' => 'required',
            'quantity' => 'required|min:0',
            'price' => 'required|min:0',
        ];
    }

    public function attributes(): array
    {
        return [
            'wallet_id' => 'Dompet',
            'item_name' => 'Nama',
            'quantity' => 'Kuantitas',
            'price' => 'Harga',
        ];
    }
}
