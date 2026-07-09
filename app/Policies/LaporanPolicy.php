<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    public function update(User $user, Laporan $laporan): bool
    {
        return (int) $laporan->user_id === (int) $user->id;
    }

    public function delete(User $user, Laporan $laporan): bool
    {
        return (int) $laporan->user_id === (int) $user->id;
    }
}
