<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseBan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'journal_id',
        'participant_id',
        'customer_id',
        'location_id',
        'ban_start',
        'ban_end',
    ];

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    protected function casts(): array
    {
        return [
            'ban_start' => 'datetime:Y-m-d',
            'ban_end' => 'datetime:Y-m-d',
        ];
    }
}
