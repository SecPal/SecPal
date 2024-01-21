<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function checkPassword($currentPassword): bool
    {
        return Hash::check($currentPassword, $this->password);
    }

    public function changePassword($newPassword): void
    {
        $this->password = Hash::make($newPassword);
        $this->save();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function timeTrackers(): HasMany
    {
        return $this->hasMany(TimeTracker::class);
    }
}
