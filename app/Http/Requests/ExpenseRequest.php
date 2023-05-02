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
        ];
    }

    public function putMethod(): array
    {

        return [
            'expense_number'   => [
                'required',
                Rule::unique('expenses')->where(function ($q) {
                    $q->where('expense_number', $this->expense_number);
                    $q->where('school_id', session('school_id'));
                    $q->whereNull('deleted_at');
                })->ignore($this->expense->id, 'id')
            ],
            'expense_date' => 'required|date',
            'status'        => 'nullable',
            'requested_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
        ];
    }
}
