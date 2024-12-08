<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\JsonResource;

class IbanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $decryptedIban = $this->iban ? Crypt::decryptString($this->iban) : null;

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'iban' => $decryptedIban ? $this->maskIban($decryptedIban) : null,
        ];
    }

    /**
     * Mask the IBAN for security purposes.
     *
     * @param  string  $iban
     * @return string
     */
    private function maskIban(string $iban): string
    {
        return str_repeat('*', 10) . substr($iban, -4);
    }
}
