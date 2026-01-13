<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ref_group',
        'ref_code',
    ];

    public function item()
    {
        return $this->hasMany(Item::class);
    }
}
