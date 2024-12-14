<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingReportByAreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('admin')->check();;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // "from_master_sub_area_id" => "nullable",
            // "to_master_sub_area_id" =>[
            //     Rule::requiredIf(function(){
            //         return $this->from_master_sub_area_id;
            //     })
            // ],
            "area_type" => "nullable",
            "trip_number" => "nullable",
            "datetime_departure" => "nullable|date_format:Y-m-d",
        ];
    }
}
