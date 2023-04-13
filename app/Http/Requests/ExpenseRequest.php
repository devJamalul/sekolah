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
                    $q->where('school_id',$this->school_id);
                })
            ],
            'expense_date' => 'required|date',
            'requested_by' => 'required|exists:users,id',
            'approved_by' => 'required|exists:users,id',
        ];
    }

    public function putMethod(): array
    {

        return [
            'expense_date' => 'required|date',
            'requested_by' => 'required|exists:users,id',
            'approved_by' => 'required|exists:users,id',
        ];
    }
}
