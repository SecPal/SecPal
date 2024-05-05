<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lastname',
        'firstname',
        'date_of_birth',
        'street',
        'number',
        'zipcode',
        'city',
        'ban_since',
        'ban_until',
        'journal_id',
    ];

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    public function trespasses(): HasMany
    {
        return $this->hasMany(Trespass::class);
    }

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'datetime:Y-m-d',
            'ban_since' => 'datetime:Y-m-d',
            'ban_until' => 'datetime:Y-m-d',
        ];
    }
}
