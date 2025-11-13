<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Prestasi;

class PrestasiPolicy
{
    public function verify(User $user): bool
    {
        return in_array($user->role, ['admin', 'kesiswaan']);
    }
}
