<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, $ability, Note $note): bool
    {
        return $user->id === $note->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Note $note) {}

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Note $note) {}

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Note $note) {}

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Note $note) {}
}
