<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Models;

use Guava\Sqids\Concerns\HasSqids;
use Guava\Sqids\Sqids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mews\Purifier\Casts\CleanHtml;
use Overtrue\LaravelVersionable\Versionable;

class Journal extends Model
{
    use HasFactory, HasSqids, SoftDeletes, Versionable;

    protected $fillable = [
        'location_id',
        'category_id',
        'description',
        'measures',
        'area',
        'involved',
        'rescue_involved',
        'fire_involved',
        'police_involved',
        'reported_by',
        'entry_by',
        'review_required',
        'incident_time',
    ];

    protected $versionable = [
        'category_id',
        'description',
        'measures',
        'area',
        'involved',
        'rescue_involved',
        'fire_involved',
        'police_involved',
        'reported_by',
        'review_required',
        'incident_time',
    ];

    protected function getSqids(): Sqids
    {
        return Sqids::make()
            ->minLength(5)
            ->alphabet('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
            ->salt(); // This will use the model's class name as the salt, so every model generates different IDs
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    protected function entryBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entry_by');
    }

    protected function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function houseBanParticipants(): HasManyThrough
    {
        return $this->hasManyThrough(
            Participant::class,
            HouseBan::class,
            'journal_id', // Foreign key on HouseBan model...
            'id', // Foreign key on Participant model...
            'id', // Local key on Journal model...
            'participant_id' // Local key on HouseBan model...
        );
    }

    public function trespassParticipants(): HasManyThrough
    {
        return $this->hasManyThrough(
            Participant::class,
            Trespass::class,
            'journal_id', // Foreign key on Trespass model...
            'id', // Foreign key on Participant model...
            'id', // Local key on Journal model...
            'participant_id' // Local key on Trespass model...
        );
    }

    // Function to get all unique Participants related to a Journal through both HouseBan and Trespass
    public function participants()
    {
        $houseBanParticipants = $this->houseBanParticipants()->get()->keyBy('id');
        $trespassParticipants = $this->trespassParticipants()->get()->keyBy('id');

        return $houseBanParticipants->concat($trespassParticipants)->unique('id');
    }

    protected function casts(): array
    {
        return [
            'incident_time' => 'datetime',
            'description' => CleanHtml::class,
            'measures' => CleanHtml::class,
        ];
    }
}
