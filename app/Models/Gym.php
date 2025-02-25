<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    /** @use HasFactory<\Database\Factories\GymFactory> */
    use HasFactory;

    protected $guarded = [];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    function chenkis()
    {
        return $this->hasMany(Chenkis::class);
    }
}
