<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pelanggaran;

class PelanggaranPolicy
{
    public function view(User $user): bool
    {
        return in_array($user->role, ['admin', 'kesiswaan', 'guru']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'kesiswaan', 'guru']);
    }

    public function verify(User $user): bool
    {
        return in_array($user->role, ['admin', 'kesiswaan']);
    }

    public function delete(User $user, Pelanggaran $pelanggaran): bool
    {
        return in_array($user->role, ['admin', 'kesiswaan']);
    }
}
