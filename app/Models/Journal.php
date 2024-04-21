<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelVersionable\Versionable;

class Journal extends Model
{
    use HasFactory, SoftDeletes, Versionable;

    protected $fillable = [
        'text',
        'actions',
        'involved',
        'review_required',
        'reference',
        'incident_time',
    ];

    protected $versionable = [
        'text',
        'actions',
        'involved',
        'review_required',
        'reference',
        'incident_time',
    ];

    protected function reportedBy(): BelongsTo
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

    protected function casts(): array
    {
        return [
            'incident_time' => 'datetime',
        ];
    }
}
