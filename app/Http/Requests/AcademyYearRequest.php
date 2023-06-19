<?php

namespace App\Http\Requests;

use App\Models\AcademicYear;
use App\Rules\AcademicYearRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcademyYearRequest extends FormRequest
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
            'school_id' => 'required',
            'academic_year_name'      => [
                Rule::unique('academic_years')->where(function ($q) {
                    $q->where([
                        'academic_year_name' => $this->academic_year_name,
                        'school_id' => session('school_id'),
                    ]);
                    $q->whereNull('deleted_at');
                }),
                'years_formatted',
                'valid_year'
            ],
            'status_years' => 'required'
        ];
    }



    public function putMethod(): array
    {
        return [
            'school_id' => 'required',
            'academic_year_name'      => [
                Rule::unique('academic_years')->where(function ($q) {
                    $q->where([
                        'academic_year_name' => $this->academic_year_name,
                        'school_id' => session('school_id'),
                    ]);
                    $q->whereNull('deleted_at');
                })->ignore($this->academy_year->id, 'id'),
                'years_formatted',
                'valid_year'
            ],
            'status_years' => 'required'
        ];
    }


    public function withValidator($validator)
    {
        $validator->addExtension('years_formatted', function ($attribute, $value, $parameters, $validator) {
            return preg_match_all("/\b\d{4,4}\b/", $value) >= 2;
        });

        $validator->addExtension('valid_year', function ($attribute, $value, $parameters, $validator) {

            $years = preg_match_all("/\b\d{4,4}\b/", $value, $result);
            if (isset($result[0]) && count($result[0]) >= 2) {
                $startYear = $result[0][0];
                $endYear = $result[0][1];

                if ($endYear - $startYear > 1) return false;
                return $startYear < $endYear;
            }

            return false;
        });
    }

    public function attributes()
    {
        return [
            'academic_year_name' => 'tahun akademik',
            'status_years' => 'status tahun akademik'
        ];
    }

    public function messages()
    {
        return [
            'academic_year_name.years_formatted' => 'format Tahun Akademik tidak sesuai',
            'academic_year_name.valid_year' => 'Tahun Akademik awal dan akhir tidak bisa pada tahun yang sama'
        ];
    }
}
