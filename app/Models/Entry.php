<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    /** @use HasFactory<\Database\Factories\EntryFactory> */
    use HasFactory;

    protected $fillable = [
        'member_id',
        'gym_id',
        'created_by',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
