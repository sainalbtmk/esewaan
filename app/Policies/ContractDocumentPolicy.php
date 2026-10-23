<?php

namespace App\Policies;

use App\Models\ContractDocument;
use App\Models\RentalContract;
use App\Models\User;

class ContractDocumentPolicy
{
    public function view(User $user, ContractDocument $document): bool
    {
        if (! $user->isAdmin()) {
            return false;
        }

        $document->loadMissing('rentalContract.premise');

        return $this->canAccessContract($user, $document->rentalContract);
    }

    public function create(User $user, RentalContract $contract): bool
    {
        if (! $user->isAdmin()) {
            return false;
        }

        $contract->loadMissing('premise');

        return $this->canAccessContract($user, $contract);
    }

    private function canAccessContract(User $user, ?RentalContract $contract): bool
    {
        if ($contract === null) {
            return false;
        }

        if ($user->isAdminHq()) {
            return true;
        }

        return $user->isAdminNegeri()
            && filled($user->negeri)
            && $contract->premise?->negeri === $user->negeri;
    }
}
