<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'assigned_gym',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function gyms()
    {
        return $this->hasMany(Gym::class);
    }

    public function allowedGym($gymId)
    {
        if($this->hasRole('owner')) {
            $gyms = $this->gyms->pluck('id')->toArray();
            if (!in_array($gymId, $gyms)) {
                return false;
            }
        }
        if($this->hasRole('admin')) {
            if($this->assigned_gym != $gymId) {
                return false;
            }
        }
        return true;
    }
}
