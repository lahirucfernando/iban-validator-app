<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\IbanResource;
use App\Http\Requests\StoreIbanRequest;
use App\Interfaces\IbanRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class IbanController extends Controller
{

    public function __construct(
        protected IbanRepositoryInterface $ibanRepository
    ) {}

    /**
     * @OA\Post(
     *     path="/api/save-iban",
     *     summary="Save a user's IBAN number",
     *     description="Allows an authenticated user to save their IBAN number.",
     *     operationId="saveIban",
     *     tags={"IBAN"},
     *     security={{
     *         "sanctum": {}
     *     }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="The IBAN number to be saved",
     *         @OA\JsonContent(
     *             required={"iban"},
     *             @OA\Property(property="iban", type="string", example="AL35202111090000000001234567")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="IBAN saved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="iban", type="string", example="AL35202111090000000001234567"),
     *             @OA\Property(property="message", type="string", example="IBAN saved successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - User needs to be authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred while saving IBAN number.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/iban-number-list",
     *     summary="Get paginated list of IBAN numbers",
     *     description="This endpoint returns a paginated list of IBAN numbers. Authentication is required.",
     *     tags={"IBAN"},
     *     security={{
     *         "sanctum": {}
     *     }},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully fetched the IBAN numbers list",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserWithIban")
     *             ),
     *             @OA\Property(
     *                 property="current_page",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="per_page",
     *                 type="integer",
     *                 example=10
     *             ),
     *             @OA\Property(
     *                 property="total",
     *                 type="integer",
     *                 example=50
     *             ),
     *             @OA\Property(
     *                 property="last_page",
     *                 type="integer",
     *                 example=5
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access.",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. Token is invalid or not provided.",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error.",
     *     ),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {
            $list = $this->ibanRepository->list();
            return IbanResource::collection($list);
        } catch (\Throwable $e) {
            Log::error('list() IBAN list Exception: ' . $e->getMessage());
            return ApiResponse::error('An unexpected error occurred in IBAN number list.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
