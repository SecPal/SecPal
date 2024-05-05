<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trespass extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'journal_id',
        'participant_id',
        'charge_filed_by',
    ];

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function chargeFiledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'charge_filed_by');
    }
}
