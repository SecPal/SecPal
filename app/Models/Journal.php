<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelVersionable\Versionable;

class Journal extends Model
{
    use HasFactory, SoftDeletes, Versionable;

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

    protected function casts(): array
    {
        return [
            'incident_time' => 'datetime',
        ];
    }
}
