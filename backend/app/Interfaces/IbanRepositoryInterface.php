<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

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

    /**
     * Get IBAN number list
     * 
     * @return LengthAwarePaginator
     */
    public function list();
}
