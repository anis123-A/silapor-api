<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordUpdater
{
    public function update(User $user, string $currentPassword, string $newPassword, bool $rejectSamePassword = false): ?array
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return [
                'status' => 400,
                'message' => 'Kata sandi lama salah!',
            ];
        }

        if ($rejectSamePassword && Hash::check($newPassword, $user->password)) {
            return [
                'status' => 422,
                'message' => 'Kata sandi baru tidak boleh sama dengan kata sandi lama!',
            ];
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return null;
    }
}
