<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassroomRequest extends FormRequest
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
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'name'      => [
                'required',
                Rule::unique('classrooms')->where(function ($q) {
                    $q->where('name', $this->name);
                    $q->where('school_id', $this->school_id);
                    $q->where('academic_year_id', $this->academic_year_id);
                    $q->where('grade_id', $this->grade_id);
                })
            ]
        ];
    }

    public function putMethod(): array
    {

        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'name'      => [
                'required',
                Rule::unique('classrooms')->where(function ($q) {
                    $q->where('name', $this->name);
                    $q->where('school_id', $this->school_id);
                    $q->where('academic_year_id', $this->academic_year_id);
                    $q->where('grade_id', $this->grade_id);
                })->ignore($this->classroom->id)
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'academic_year_id' => 'Tahun Akademik',
            'grade_id' => 'Tingkatan',
            'name' => 'Nama',
        ];
    }
}
