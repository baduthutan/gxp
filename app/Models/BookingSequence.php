<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'date_sequence',
        'current_sequence',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
