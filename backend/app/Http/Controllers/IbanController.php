<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreIbanRequest;
use App\Interfaces\IbanRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class IbanController extends Controller
{
    
    public function __construct(
        protected IbanRepositoryInterface $ibanRepository
    ) {}

    public function store(StoreIbanRequest $request)
    {
        try {
            $user = Auth::user();
            $ibanNumber = $request->iban;
            $this->ibanRepository->updateIban($user, $ibanNumber);
            return ApiResponse::success([
                'ibna' => $ibanNumber,
            ], 'IBAN saved successfully.', Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            Log::error('store() Save IBAN Exception: ' . $e->getMessage());
            return ApiResponse::error('An unexpected error occurred while save IBAN number.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
