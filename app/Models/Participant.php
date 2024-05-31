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
        ];
    }

    public static function createOrUpdate(array $data): Participant
    {
        return self::updateOrCreate(
            [
                'lastname' => $data['lastname'],
                'firstname' => $data['firstname'],
                'date_of_birth' => $data['date_of_birth'],
            ],
            $data
        );
    }
}
