<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolProfileRequest extends FormRequest
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
            'school_name' => 'required|string|max:255',
            'province'   => 'required|string|max:100',
            'city'  => 'required|string|max:100',
            'postal_code' => 'required|string|max:255',
            'address'   => 'required|string|max:100',
            'grade'  => 'required|string|max:100',
            'email' => 'required|string|max:255',
            'phone'   => 'required|string|max:100',
        ];
    }

    public function attributes()
    {
        return [
            'school_name' => 'nama sekolah',
            'province'   => 'provinsi',
            'city'  => 'kota',
            'postal_code' => 'kode pos',
            'address'   => 'alamat',
            'grade'  => 'tingkatan',
            'phone'   => 'nomor telepon',
            'foundation_head_name'   => 'nama pimpinan sekolah',
            'foundation_head_tlpn'   => 'nomor telepon pimpinan sekolah',
        ];
    }
}
