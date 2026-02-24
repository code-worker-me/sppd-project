<?php

namespace App\Policies;

use App\Models\DataSppd;
use App\Models\User;

class SppdPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm', 'staff_keuangan']);
    }

    public function view(User $user, DataSppd $dataSppd): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm', 'staff_keuangan']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm']);
    }

    public function update(User $user, DataSppd $dataSppd): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm']);
    }

    public function delete(User $user, DataSppd $dataSppd): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm']);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff_sdm']);
    }
}
