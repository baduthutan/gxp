<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'slug',
        'page_title',
        'page_content',
        'parent_page_id',
        'is_dropdown',
        'url',
        'type',
        'orderNumber',
    ];
}
