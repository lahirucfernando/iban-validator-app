<?php

namespace App\Interfaces;

use App\Models\User;

interface IbanRepositoryInterface
{
    /**
     * Update user IBAN number 
     * 
     * @param User $user
     * @param string $iban
     * @return void
     */
    public function updateIban(User $user,string $iban);
}
