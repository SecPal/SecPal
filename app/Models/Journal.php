<?php

namespace App\Models;

use Guava\Sqids\Concerns\HasSqids;
use Guava\Sqids\Sqids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class, 'participant_id');
    }

    protected function casts(): array
    {
        return [
            'incident_time' => 'datetime',
        ];
    }
}
