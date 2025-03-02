<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionFactory> */
    use HasFactory, AuthorizesRequests, HasUuids;

    protected $fillable = ['start_date', 'end_date', 'member_id', 'created_by', 'updated_by', 'membership_id', 'gym_id'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
