<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function houseBans(): HasMany
    {
        return $this->hasMany(HouseBan::class);
    }

    public function latestActiveHouseBan($journal_id = null, $incidentDate = null): HasOne
    {
        if ($incidentDate == null) {
            $incidentDate = Carbon::now();
        }

        $query = $this->hasOne(HouseBan::class)
            ->where('ban_start', '<=', $incidentDate)
            ->where('ban_end', '>', $incidentDate);

        if ($journal_id !== null) {
            $query = $query->where('journal_id', '!=', $journal_id);
        }

        return $query->latest('ban_end');
    }

    public function latestHouseBan(): HasOne
    {
        return $this->hasOne(HouseBan::class)->latest('ban_end');
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
