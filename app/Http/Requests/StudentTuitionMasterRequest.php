<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentTuitionMasterRequest extends FormRequest
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
    
    protected function postMethod(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'tuition_id' => 'required|exists:tuitions,id',
            'price' => 'numeric',
            'note' => 'nullable',
        ];
    }

    protected function putMethod(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'tuition_id' => 'required|exists:tuitions,id',
            'price' => 'numeric',
            'note' => 'nullable',
        ];
    }
}
