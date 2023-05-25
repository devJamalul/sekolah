<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseRequest extends FormRequest
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
            'note' => 'Deskripsi',
            'expense_number' => 'Nomor Pengeluaran',
            'expense_date' => 'Tanggal pengeluaran',
            'requested_by' => 'Diminta oleh',
            'approved_by' => 'Disetujui oleh',
        ];
    }

    public function postMethod(): array
    {
        return [
            'expense_number'      => [
                'required',
                Rule::unique('expenses')->where(function ($q) {
                    $q->where('expense_number', $this->expense_number);
                    $q->where('school_id',  session('school_id'));
                    $q->whereNull('deleted_at');
                })
            ],
            'expense_date' => 'required|date',
            'status'        => 'nullable',
            'requested_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
            'item_name' => 'required|string',
            'price' => 'required|string',
            'wallet_id' => 'required|exists:wallets,id',
            'quantity' => 'required|string'
        ];
    }

    public function putMethod(): array
    {

        return [
            'expense_number' => [
                'required',
                Rule::unique('expenses')->where(function($q){
                    $q->where('expense_number', $this->expense_number);
                    $q->where('school_id',  session('school_id'));
                    $q->whereNull('deleted_at');
                })->ignore($this->expense->id, 'id')
            ],
            'array_item_name' => 'required|array',
            'array_item_name.*' => 'required|string',
            'array_wallet_id' => 'required|array',
            'array_wallet_id.*' => 'required|exists:wallets,id',
            'array_price' => 'required|array',
            'array_price.*' => 'required|string',
            'array_quantity' => 'required|array',
            'array_quantity.*' => 'required|string',
            'expense_date' => 'required|date',
            'status'        => 'nullable',
            'requested_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
        ];
    }

}
