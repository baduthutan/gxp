<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'booking_number',
        'schedule_id',
        'area_type',
        'from_master_area_id',
        'from_master_area_name',
        'from_master_sub_area_id',
        'from_master_sub_area_name',
        'to_master_area_id',
        'to_master_area_name',
        'to_master_sub_area_id',
        'to_master_sub_area_name',
        'vehicle_name',
        'vehicle_number',
        'datetime_departure',
        'trip_number',
        'schedule_type',
        'user_id',
        'customer_phone',
        'customer_name',
        'customer_email',
        'qty_adult',
        'qty_baby',
        'base_price',
        'total_base_price',
        'flight_number',
        'flight_info',
        'notes',
        'luggage_qty',
        'luggage_price',
        'overweight_luggage_qty',
        'overweight_luggage_price',
        'special_request',
        'special_area_id',
        'special_area_detail',
        'regional_name',
        'extra_price',
        'voucher_id',
        'promo_price',
        'sub_total_price',
        'fee_price',
        'grand_total_price',
        'booking_status',
        'payment_status',
        'payment_method',
        'payment_token',
        'total_payment',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function booking_customers()
    {
        return $this->hasMany(BookingCustomer::class,'booking_id', 'id');
    }
}
