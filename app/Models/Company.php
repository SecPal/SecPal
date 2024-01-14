<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
        'active_since',
        'active_until',
    ];

    protected $casts = [
        'active_since' => 'date',
        'active_until' => 'date',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
