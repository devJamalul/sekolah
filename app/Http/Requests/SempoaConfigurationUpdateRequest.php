<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SempoaConfigurationUpdateRequest extends FormRequest
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
            'token' => 'required|string|max:191',
            'tuition_debit_account' => 'nullable|string|max:191',
            'tuition_credit_account' => 'nullable|string|max:191',
            'invoice_debit_account' => 'nullable|string|max:191',
            'invoice_debit_account' => 'nullable|string|max:191',
            'expense_credit_account' => 'nullable|string|max:191',
            'expense_credit_account' => 'nullable|string|max:191',
        ];
    }
}
