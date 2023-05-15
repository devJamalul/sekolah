<?php

namespace App\Http\Requests;

use App\Rules\UniqueGradeName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradeRequest extends FormRequest
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
            'grade_name'      => [
                'required',
                Rule::unique('grades')->where(function ($q) {
                    $q->where('grade_name', $this->grade_name);
                    $q->where('school_id', session('school_id'));
                    $q->whereNull('deleted_at');
                })
            ],
        ];
    }

    public function putMethod(): array
    {
        return [
            'grade_name'      => [
                'required',
                Rule::unique('grades')->where(function ($q) {
                    $q->where('grade_name', $this->grade_name);
                    $q->where('school_id', session('school_id'));
                    $q->whereNull('deleted_at');
                })->ignore($this->grade->id, 'id')
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'grade_name' => 'Nama Tingkatan',
        ];
    }
}
