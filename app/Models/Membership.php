<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    /** @use HasFactory<\Database\Factories\MembershipFactory> */
    use HasFactory;

    protected $guarded = [];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
