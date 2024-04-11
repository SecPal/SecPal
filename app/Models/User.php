<?php
/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\ShiftStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, HasRolesAndPermissions, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function checkPassword($currentPassword): bool
    {
        return Hash::check($currentPassword, $this->password);
    }

    public function changePassword($newPassword): void
    {
        $this->password = Hash::make($newPassword);
        $this->save();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class);
    }

    public function createTimetrackerForUser($locationId, $event, $plan_time): void
    {
        $this->timeTrackers()->create([
            'location_id' => $locationId,
            'event' => $event,
            'real_time' => Carbon::now(),
            'plan_time' => $plan_time,
            'entry_by' => $this->id,
        ]);
    }

    public function timeTrackers(): HasMany
    {
        return $this->hasMany(TimeTracker::class);
    }

    public function isOnDuty(): bool
    {
        $user = $this->getUser();
        // Check if the user is on shift
        $shiftData = $this->getLocationIdIfOnShift($user);
        if ($shiftData['onDuty']) {
            return true;
        }

        return false;
    }

    /**
     * Get the location_id for the user as an attribute.
     */
    public function getLocationIdAttribute(): ?int
    {
        return $this->getLocationId();
    }

    public function getLocationId(): ?int
    {
        $user = $this->getUser();
        // get the LocationId, if the user is on shift
        $shiftData = $this->getLocationIdIfOnShift($user);
        if ($shiftData['locationId']) {
            return $shiftData['locationId'];
        }

        return null;
    }

    // Extracted method that retrieves the User entity
    private function getUser(): ?User
    {
        $user = Auth::user();
        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    /**
     * Check if the user is on shift (including break time) and has not ended or aborted the shift,
     * then return the corresponding location_id
     */
    private function getLocationIdIfOnShift(User $user): array
    {
        // Retrieve the last record for the given user
        $timeTracker = TimeTracker::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
        // If there's no record, then the user isn't on a shift
        if (! $timeTracker) {
            return ['onDuty' => false, 'locationId' => null];
        }
        // If the last 'event' is 'ShiftEnd' or 'ShiftAbort', the user isn't on a shift
        if ($timeTracker->event === ShiftStatus::ShiftEnd || $timeTracker->event === ShiftStatus::ShiftAbort) {
            return ['onDuty' => false, 'locationId' => null];
        }

        // For all other 'event' types ('ShiftStart', 'BreakStart', 'BreakEnd', 'BreakAbort'), it means the user is on a shift
        return ['onDuty' => true, 'locationId' => $timeTracker->location_id];
    }
}
