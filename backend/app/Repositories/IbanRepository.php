<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\IbanEncryptionService;
use App\Interfaces\IbanRepositoryInterface;

class IbanRepository implements IbanRepositoryInterface
{

    public function __construct(
        protected IbanEncryptionService $ibanEncryptionService
    ) {}

    /**
     * {@inheritDoc}
     */
    public function updateIban(User $user, string $iban)
    {
        // Encrypt the IBAN before saving
        $encryptedIban = $this->ibanEncryptionService->encryptIban($iban);
        
        // Save the encrypted IBAN in the user table
        $user->iban = $encryptedIban;
        $user->save();
    }

    /**
     * {@inheritDoc}
     */
    public function list()
    {
        return User::select('uuid', 'name', 'email', 'iban')
            ->paginate(config('pagination.per_page'));
    }
}
