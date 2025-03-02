<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'ci', 'email', 'phone'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}
