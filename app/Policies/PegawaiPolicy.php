<?php

namespace App\Policies;

use App\Models\DataDiri;
use App\Models\User;

class PegawaiPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm', 'staff_keuangan']);
    }

    public function view(User $user, DataDiri $dataDiri): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm', 'staff_keuangan']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin']);
    }

    public function update(User $user, DataDiri $dataDiri): bool
    {
        return in_array($user->role, ['admin']);
    }

    public function delete(User $user, DataDiri $dataDiri): bool
    {
        return in_array($user->role, ['admin']);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, ['admin']);
    }
}
