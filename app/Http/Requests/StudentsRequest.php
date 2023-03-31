<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentsRequest extends FormRequest
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
            'academic_year_id' => 'required|exists:academic_years,id',

            'name' => 'required',
            'email' => 'nullable|email',
            'dob' => 'required',
            'religion' => 'required',
            'gender' => 'required|max:1',
            'address' => 'required',
            'phone_number' => 'nullable|max:15',
            'no_kartu_keluarga' => 'nullable|max:25',
            'nik' => 'required|numeric|max_digits:16',
            'nis' => 'nullable|numeric|max_digits:20',
            'nisn' => 'nullable|numeric|max_digits:10',
            
            'father_name' => 'required',
            'father_work' => 'nullable',
            'father_phone_number' => 'nullable|max:15',
            'father_address' => 'nullable',

            'mother_name' => 'required',
            'mother_work' => 'nullable',
            'mother_phone_number' => 'nullable|max:15',
            'mother_address' => 'nullable',

            'guardian_name' => 'nullable',
            'guardian_work' => 'nullable',
            'guardian_phone_number' => 'nullable|max:15',
            'guardian_address' => 'nullable',

            'tuitions' => 'nullable|array',
            'tuitions.*' => "nullable|numeric",
        ];
    }

    protected function putMethod(): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',

            'name' => 'required',
            'email' => 'nullable|email',
            'dob' => 'required',
            'gender' => 'required|max:1',
            'address' => 'required',
            'religion' => 'required',
            'phone_number' => 'nullable|max:15',
            'no_kartu_keluarga' => 'nullable|max:25',
            'nik' => 'required|numeric|max_digits:16',
            'nis' => 'nullable|numeric|max_digits:20',
            'nisn' => 'nullable|numeric|max_digits:10',

            'father_name' => 'required',
            'father_work' => 'nullable',
            'father_phone_number' => 'nullable|max:15',
            'father_address' => 'nullable',

            'mother_name' => 'required',
            'mother_work' => 'nullable',
            'mother_phone_number' => 'nullable|max:15',
            'mother_address' => 'nullable',

            'guardian_name' => 'nullable',
            'guardian_work' => 'nullable',
            'guardian_phone_number' => 'nullable|max:15',
            'guardian_address' => 'nullable',

            'selected_tuitions' => 'nullable|array',
            'selected_tuitions.*' => "nullable|numeric",
            'tuitions' => 'nullable|array',
            'tuitions.*' => "nullable|numeric",
        ];
    }
}
