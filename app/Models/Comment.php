<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'comment',
        'police_report',
        'report_file',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
