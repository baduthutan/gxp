<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_name',
        'customer_phone',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
