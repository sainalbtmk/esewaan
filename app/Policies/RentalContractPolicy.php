<?php

namespace App\Policies;

use App\Models\RentalContract;
use App\Models\User;

class RentalContractPolicy
{
    /**
     * View an approved rental contract in the Kontrak Sewaan module.
     */
    public function viewKontrak(User $user, RentalContract $contract): bool
    {
        if (! $contract->isHqApproved()) {
            return false;
        }

        if ($user->isAdminHq()) {
            return true;
        }

        $contract->loadMissing('premise');

        return $user->isAdminNegeri()
            && filled($user->negeri)
            && $contract->premise?->negeri === $user->negeri;
    }

    /**
     * Whether the user may access the contract for document operations.
     */
    public function accessDocuments(User $user, RentalContract $contract): bool
    {
        if (! $user->isAdmin()) {
            return false;
        }

        if ($user->isAdminHq()) {
            return true;
        }

        $contract->loadMissing('premise');

        return $user->isAdminNegeri()
            && filled($user->negeri)
            && $contract->premise?->negeri === $user->negeri;
    }
}
