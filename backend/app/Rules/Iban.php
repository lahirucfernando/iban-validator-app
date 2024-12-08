<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Iban implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove spaces and convert to uppercase
        $iban = strtoupper(str_replace(' ', '', $value));

        // Check for empty IBAN or invalid characters
        if (!ctype_alnum($iban) || strlen($iban) < 15) {
            $fail('The :attribute is invalid.');
            return;
        }

        // Rearrange the IBAN (move the first four characters to the end)
        $ibanRearranged = substr($iban, 4) . substr($iban, 0, 4);

        // Convert letters to numbers (A=10, B=11, ..., Z=35)
        $ibanNumeric = '';
        foreach (str_split($ibanRearranged) as $char) {
            // For alphabetic characters, convert to number (A=10, B=11, ..., Z=35)
            $ibanNumeric .= ctype_alpha($char) ? (ord($char) - 55) : $char;
        }

        // Ensure the IBAN number string is valid and numeric for bcmod
        if (!ctype_digit($ibanNumeric)) {
            $fail('The :attribute is invalid.');
            return;
        }

        // Perform the modulo 97 check
        if (bcmod($ibanNumeric, 97) !== '1') {
            $fail('The :attribute is invalid.');
        }
    }
}
