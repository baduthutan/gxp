<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'from_type',
        'from_master_area_id',
        'from_master_sub_area_id',
        'to_master_area_id',
        'to_master_sub_area_id',
        'vehicle_name',
        'vehicle_number',
        'total_seat',
        'is_available',
        'photo',
        'price',
        'driver_contact',
        'notes',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function from_master_area()
    {
        return $this->belongsTo(MasterArea::class, 'from_master_area_id', 'id')->withDefault([
            'id' => 0,
            'name' => "",
            'area_type' => "",
            'is_active' => 0,
        ]);
    }

    public function from_master_sub_area()
    {
        return $this->belongsTo(MasterSubArea::class, 'from_master_sub_area_id', 'id')->withDefault([
            'id' => 0,
            'master_area_id' => 0,
            'name' => "",
            'area_type' => "",
            'is_active' => 0,
        ]);
    }


    public function to_master_area()
    {
        return $this->belongsTo(MasterArea::class, 'to_master_area_id', 'id')->withDefault([
            'id' => 0,
            'name' => "",
            'area_type' => "",
            'is_active' => 0,
        ]);
    }

    public function to_master_sub_area()
    {
        return $this->belongsTo(MasterSubArea::class, 'to_master_sub_area_id', 'id')->withDefault([
            'id' => 0,
            'master_area_id' => 0,
            'name' => "",
            'area_type' => "",
            'is_active' => 0,
        ]);
    }

    public function bookings(){
        return $this->hasmany(Booking::class, 'schedule_id', 'id')->whereRaw('schedule_type = "charter"');
    }
}
