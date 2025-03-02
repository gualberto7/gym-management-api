<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    /** @use HasFactory<\Database\Factories\GymFactory> */
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'address', 'phone', 'email', 'website', 'user_id'];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }
}
