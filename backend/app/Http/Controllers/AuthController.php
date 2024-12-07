<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\TokenService;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRegisterRequest;
use App\Interfaces\AuthRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="IBAN Validation App API"
 * )
 */
class AuthController extends Controller
{
    public function __construct(
        protected TokenService $tokenService,
        protected AuthRepositoryInterface $authRepository
    ) {}

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     description="Registers a new user and returns the user object with a token.",
     *     operationId="registerUser",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="David Miller"),
     *             @OA\Property(property="email", type="string", format="email", example="devid@example.com"),
     *             @OA\Property(property="password", type="string", example="password@123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password@123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="your-token-here")
     *         )
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Validation error - Invalid input data",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required.")),
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email has already been taken.")),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="The password confirmation does not match.")),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred while registering the user.")
     *         )
     *     )
     * )
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $user = $this->authRepository->register($request->all());
            $token = $this->tokenService->createToken($user);
            return ApiResponse::success([
                'user' => new UserResource($user),
                'token' => $token,
            ], 'User created successfully', Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            Log::error('register() User Register Exception: ' . $e->getMessage());
            return ApiResponse::error('An unexpected error occurred while registering the user.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
