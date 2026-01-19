<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    protected $fillable = [
        'serial_number',
        'item_user',
        'device_name',
        'department',
        'reference_number',
        'value',
        'status',
        'photo',
        'category_id',
        'police_report',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
