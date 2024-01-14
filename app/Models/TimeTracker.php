<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeTracker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'event',
        'real_time',
        'plan_time',
        'comment',
    ];

    protected $casts = [
        'real_time' => 'datetime',
        'plan_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
