<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceDetailRequest extends FormRequest
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

    public function attributes(): array
    {
        return [
            'item_name' => 'nama barang',
            'price' => 'harga'
        ];
    }

    public function postMethod(): array
    {
        return [
            'item_name' => [
                'required',
                Rule::unique('invoice_details')->where(function ($q) {
                    $q->where('invoice_id', $this->invoice->id);
                    $q->where('item_name', $this->item_name);
                    $q->whereNull('deleted_at');
                })
            ],
            'price' => 'required|min:0',
        ];
    }

    public function putMethod(): array
    {
        return [
            'item_name'      => [
                'required',
                Rule::unique('invoice_details')->where(function ($q) {
                    $q->where('invoice_id', $this->invoice->id);
                    $q->where('item_name', $this->item_name);
                    $q->whereNull('deleted_at');
                })->ignore($this->invoice_detail->id, 'id')
            ],
            'price' => 'required|min:0',
        ];
    }
}
