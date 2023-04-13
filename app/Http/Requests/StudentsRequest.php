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
            'name' => 'required',
            'email' => 'nullable|email',
            'dob' => 'required',
            'religion' => 'required',
            'gender' => 'required|max:1',
            'address' => 'required',
            'phone_number' => 'nullable|max:20',
            'family_card_number' => 'required|numeric|max_digits:20',
            'nik' => 'required|numeric|max_digits:16',
            'nis' => 'nullable|numeric|max_digits:20',
            'nisn' => 'nullable|numeric|max_digits:10',
            
            'father_name' => 'nullable',
            'father_address' => 'nullable',
            'father_phone_number' => 'nullable|max:20',

            'mother_name' => 'nullable',
            'mother_address' => 'nullable',
            'mother_phone_number' => 'nullable|max:20',

            'guardian_name' => 'nullable',
            'guardian_address' => 'nullable',
            'guardian_phone_number' => 'nullable|max:20',

            'file_photo' => 'nullable|image|max:4000',
            'file_birth_certificate' => 'nullable|image|max:4000',
            'file_family_card' => 'nullable|image|max:4000',
        ];
    }

    protected function putMethod(): array
    {
        return [
            'name' => 'required',
            'email' => 'nullable|email',
            'dob' => 'required',
            'gender' => 'required|max:1',
            'address' => 'required',
            'religion' => 'required',
            'phone_number' => 'nullable|max:20',
            'family_card_number' => 'required|numeric|max_digits:20',
            'nik' => 'required|numeric|max_digits:16',
            'nis' => 'nullable|numeric|max_digits:20',
            'nisn' => 'nullable|numeric|max_digits:10',

            'father_name' => 'nullable',
            'father_address' => 'nullable',
            'father_phone_number' => 'nullable|max:20',

            'mother_name' => 'nullable',
            'mother_address' => 'nullable',
            'mother_phone_number' => 'nullable|max:20',

            'guardian_name' => 'nullable',
            'guardian_address' => 'nullable',
            'guardian_phone_number' => 'nullable|max:20',

            'file_photo' => 'nullable|image|max:4000',
            'file_birth_certificate' => 'nullable|image|max:4000',
            'file_family_card' => 'nullable|image|max:4000',
        ];
    }
}
