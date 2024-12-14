<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules =  [
            'vehicle_number' => [
                'required',
                Rule::unique('vehicles','vehicle_number'),
                'string',
                'max:255'
            ],
            'vehicle_name' => 'string|max:255|nullable',
            'driver_contact' => 'string|max:255|nullable',
            'photo',
            'notes' => 'string|nullable',
            'total_seat' => 'numeric|nullable',
        ];

        if ($this->method() == 'PUT'){
            $vehicle = $this->route('vehicle');
            $rules['vehicle_number'] = [
                'required',
                Rule::unique('vehicles','vehicle_number')->ignore($vehicle->id),
                'string',
                'max:255'
            ];
        }

        return $rules;
    }
}
