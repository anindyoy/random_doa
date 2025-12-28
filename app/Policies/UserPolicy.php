<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Tentukan apakah user bisa melihat daftar semua model (User List).
     */
    public function viewAny(User $user): bool
    {
        // Hanya user dengan attribute is_admin bernilai true yang diizinkan
        return $user->is_admin === 1;
    }

    /**
     * Tentukan apakah user bisa melihat detail model tertentu.
     */
    public function view(User $user, User $model): bool
    {
        // Opsional: Admin bisa melihat siapa saja,
        // atau user biasa hanya bisa melihat profilnya sendiri
        return $user->is_admin === 1 || $user->id === $model->id;
    }

    /**
     * Tentukan apakah user bisa membuat model baru.
     */
    public function create(User $user): bool
    {
        return $user->is_admin === 1;
    }

    /**
     * Tentukan apakah user bisa memperbarui model.
     */
    public function update(User $user, User $model): bool
    {
        // Biasanya user hanya bisa mengupdate dirinya sendiri atau dilakukan oleh admin
        return $user->is_admin === 1 || $user->id === $model->id;
    }

    /**
     * Tentukan apakah user bisa menghapus model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->is_admin === 1;
    }

    /**
     * Tentukan apakah user bisa memulihkan model (soft delete).
     */
    public function restore(User $user, User $model): bool
    {
        return $user->is_admin === 1;
    }

    /**
     * Tentukan apakah user bisa menghapus permanen model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->is_admin === 1;
    }
}