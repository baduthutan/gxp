<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterSubArea extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'master_area_id',
        'name',
        'is_active',
        'is_charter',
    ];

    protected $hidden = [
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function master_area()
    {
        return $this->belongsTo(MasterArea::class);
    }

    public function master_special_area()
    {
        return $this->hasMany(MasterSpecialArea::class);
    }
}
