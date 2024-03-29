<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TuitionTypeRequest extends FormRequest
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
            'school_id' => 'required|exists:schools,id',
            'name'      => [
                'required',
                Rule::unique('tuition_types')->where(function ($q) {
                    $q->where('name', $this->name);
                    $q->where('school_id', $this->school_id);
                })
            ],
            'generatable' => 'nullable'
        ];
    }

    public function putMethod(): array
    {

        return [
            'school_id' => 'required|exists:schools,id',
            'name'      => [
                'required',
                Rule::unique('tuition_types')->where(function ($q) {
                    $q->where('name', $this->name);
                    $q->where('school_id', $this->school_id);
                })->ignore($this->tuition_type->id)
            ],
            'generatable' => 'nullable'
        ];
    }
}
