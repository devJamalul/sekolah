<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
            'biodata' => 'nullable',
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'gender' => 'nullable',
            'religion' => 'nullable',
            'dob' => 'nullable|date',
            'phone_number' => 'nullable',
            'nik' => 'nullable|numeric',
            'nip' => 'nullable|numeric',
            'nidn' => 'nullable|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nama',
            'gender' => 'jenis kelamin',
            'religion' => 'agama',
            'dob' => 'tanggal lahir',
            'phone_number' => 'nomor telepon',
            'nik' => 'nomor KTP',
            'nip' => 'nomor induk pegawai',
            'nidn' => 'NIDN'
        ];
    }
}
