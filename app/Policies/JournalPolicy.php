<?php

namespace App\Policies;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Journal $journal): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Journal $journal): bool
    {
    }

    public function delete(User $user, Journal $journal): bool
    {
        if ($user->isAbleTo('delete-journal')) {
            return true;
        }

        return $user->isAbleTo('delete-journal', $journal->location_id);
    }

    public function restore(User $user, Journal $journal): bool
    {
    }

    public function forceDelete(User $user, Journal $journal): bool
    {
    }
}
