<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'password',
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
    ];

    public function voucher()
    {
        return $this->hasMany(Voucher::class);
    }
}
