<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShuttleScheduleResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $is_available = true;
        if ($this->total_seat - $this->seat_booked <= 0) {
            $is_available = false;
        } elseif (($this->seat_booked + request('qty_adult') + request('qty_baby')) > $this->total_seat) {
            $is_available = false;
        }

        return[
            'id' => $this->id,
            'from_type' => $this->from_type,
            'from_master_area_id' => $this->from_master_area_id,
            'from_master_area' => $this->from_master_area,
            'from_master_sub_area_id' => $this->from_master_sub_area_id,
            'from_master_sub_area' => $this->from_master_sub_area,
            'to_master_area_id' => $this->to_master_area_id,
            'to_master_area' => $this->to_master_area,
            'to_master_sub_area_id' => $this->to_master_sub_area_id,
            'to_master_sub_area' => $this->to_master_sub_area,
            'vehicle_name' => $this->vehicle_name,
            'vehicle_number' => $this->vehicle_number,
            'time_departure' => $this->time_departure,
            'is_active' => $this->is_active,
            'photo' => $this->photo,
            'price' => $this->price,
            'driver_contact' => $this->driver_contact,
            'notes' => $this->notes,
            'total_seat' => $this->total_seat,
            'luggage_price' => $this->luggage_price,
            'is_available' => $is_available,
        ];
    }
}
