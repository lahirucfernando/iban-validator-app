<?php 

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class IbanEncryptionService
{    
    /**
     * Remove spaces from the IBAN string.
     *
     * @param string $iban
     * @return string
     */
    public function cleanIban(string $iban): string
    {
        return str_replace(' ', '', $iban);  
    }

    /**
     * Encrypt the given IBAN number.
     *
     * @param string $iban
     * @return string
     */
    public function encryptIban(string $iban): string
    {   
        $cleanedIban = $this->cleanIban($iban);
        // Encrypt the cleaned IBAN
        return Crypt::encryptString($cleanedIban);
    }
}