<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingReportByVehicleRequest extends FormRequest
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
            "vehicle_number" => "nullable",
            "datetime_departure" => "nullable|date_format:Y-m-d",
        ];
    }
}
