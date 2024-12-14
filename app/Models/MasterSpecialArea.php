<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterSpecialArea extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'master_sub_area_id',
        'regional_name',
        'first_person_price',
        'extra_person_price',
        'is_active',
        'notes',
    ];

    public function master_sub_area()
    {
        return $this->belongsTo(MasterSubArea::class);
    }
}
