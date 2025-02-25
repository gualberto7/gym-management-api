<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chenkis extends Model
{
    /** @use HasFactory<\Database\Factories\ChenkisFactory> */
    use HasFactory;

    protected $fillable = [
        'member_id',
        'gym_id',
        'registred_by',
    ];
}
