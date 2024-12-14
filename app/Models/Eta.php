<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'trip_1',
        'trip_2',
    ];
}