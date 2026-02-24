<?php

namespace App\Policies;

use App\Models\DataPerjalanan;
use App\Models\User;

class PerjalananPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm', 'staff_keuangan']);
    }

    public function view(User $user, DataPerjalanan $dataPerjalanan): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm', 'staff_keuangan']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff_keuangan']);
    }

    public function update(User $user, DataPerjalanan $dataPerjalanan): bool
    {
        return in_array($user->role, ['admin', 'staff_keuangan']);
    }

    public function delete(User $user, DataPerjalanan $dataPerjalanan): bool
    {
        return in_array($user->role, ['admin', 'staff_keuangan']);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff_keuangan']);
    }
}
