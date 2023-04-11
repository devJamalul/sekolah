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
                        'school_id' => $this->school_id,
                    ]);
                }),
                'years_formatted',
                'valid_year'
            ],
            'status_years'      => [
                Rule::unique('academic_years')->where(function ($q) {
                    if (in_array($this->status_years, [AcademicYear::STATUS_CLOSED]) == false) {
                        $q->where([
                            'status_years' => $this->status_years,
                            'school_id' => $this->school_id,
                        ]);
                    }
                }),
            ],

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
                        'school_id' => $this->school_id,
                    ]);
                })->ignore($this->academy_year->id, 'id'),
                'years_formatted',
                'valid_year'
            ],
            'status_years' => 'unique_status_years'
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
                return $startYear < $endYear;
            }

            return true;
        });

        $validator->addExtension('unique_status_years', function ($attribute, $value, $parameters, $validator) {
            $academy_year = $this->academy_year;
            if (in_array($this->status_years, [AcademicYear::STATUS_CLOSED]) == false) {
                $academyYearFilter = AcademicYear::where('status_years', $value)->whereNotIn('id', [$academy_year->id])->first();
                return $academyYearFilter ? false : true;
            }
            return true;
        });
    }

    public function messages()
    {
        return [
            'academic_year_name.years_formatted' => 'The Invalid Academy years Formatted ',
            'academic_year_name.valid_year' => 'The Invalid Academy years Formatted ',
            'status_years.unique_status_years' => 'The status years has already been taken. '
        ];
    }
}
