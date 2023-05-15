<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
            'name' => 'required',
            'dob' => 'required',
            'religion' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'phone_number' => 'nullable|max:20',
            'family_card_number' => 'required|numeric|max_digits:20',
            'nik' => 'required|numeric|max_digits:16',
            'nip' => 'nullable|numeric|max_digits:20',
            'nidn' => 'nullable|numeric|max_digits:10',
            'file_photo' => 'nullable|image|max:4000',
            'file_birth_certificate' => 'nullable|image|max:4000',
            'file_family_card' => 'nullable|image|max:4000',
        ];
    }

    public function putMethod(): array
    {

        return [
            'school_id' => 'required|exists:schools,id',
            'name' => 'required',
            'dob' => 'required',
            'religion' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'phone_number' => 'nullable|max:20',
            'family_card_number' => 'required|numeric|max_digits:20',
            'nik' => 'required|numeric|max_digits:16',
            'nip' => 'nullable|numeric|max_digits:20',
            'nidn' => 'nullable|numeric|max_digits:10',
            'file_photo' => 'nullable|image|max:4000',
            'file_birth_certificate' => 'nullable|image|max:4000',
            'file_family_card' => 'nullable|image|max:4000',
        ];
    }

    public function attributes(): array
    {
        return [
            'school_id' => 'ID Sekolah',
            'name' => 'Nama',
        ];
    }
}
