<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
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
            'note' => 'deskripsi',
            'invoice_number' => 'nomor invoice',
            'invoice_date' => 'tanggal invoice',
            'due_date' => 'jatuh tempo',
        ];
    }

    public function postMethod(): array
    {
        return [
            'invoice_number' => [
                'nullable',
                Rule::unique('invoices')->where(function ($q) {
                    $q->where('invoice_number', $this->invoice_number);
                    $q->where('school_id', session('school_id'));
                    $q->whereNull('deleted_at');
                })
            ],
            'note' => 'required|string',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'item_name' => 'required|string',
            'price' => 'required|string',
        ];
    }

    public function putMethod(): array
    {
        return [
            'invoice_number'      => [
                'required',
                Rule::unique('invoices')->where(function ($q) {
                    $q->where('invoice_number', $this->invoice_number);
                    $q->where('school_id', session('school_id'));
                    $q->whereNull('deleted_at');
                })->ignore($this->invoice->id, 'id')
            ],
            'note' => 'required|string',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'invoice_detail_id' => 'required|array',
            'array_item_name' => 'required|array',
            'array_item_name.*' => 'required|string',
            'array_price' => 'required|array',
            'array_price.*' => 'required|string',
        ];
    }

    public function after()
    {
        # code...
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('price')) {
            $this->merge([
                'price' => formatAngka($this->price),
            ]);
        }
        if ($this->has('array_price')) {
            $this->merge([
                'array_price' => formatAngka($this->array_price),
            ]);
        }
    }

    // protected function passedValidation(): void
    // {
    //     if ($this->has('price')) {
    //         $this->replace(['price' => formatAngka($this->price)]);
    //     }
    // }

    protected function passedValidation(): void
    {
        if ($this->has('price')) {
            $this->replace(['price' => formatAngka($this->price)]);
        }
        if ($this->has('array_price')) {
            $this->replace(['array_price' => formatAngka($this->array_price)]);
        }
    }
}
