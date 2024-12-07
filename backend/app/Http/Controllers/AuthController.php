<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\AuthService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRegisterRequest;
use App\Interfaces\AuthRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\AuthenticationFailedException;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="IBAN Validation App API"
 * )
 */
class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
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
            $token = $this->authService->createToken($user);
            return ApiResponse::success([
                'user' => new UserResource($user),
                'token' => $token,
            ], 'User created successfully', Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            Log::error('register() User Register Exception: ' . $e->getMessage());
            return ApiResponse::error('An unexpected error occurred while registering the user.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Log in a user",
     *     description="Allows a user to log in by providing their email and password. A token is generated upon successful authentication.",
     *     operationId="loginUser",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User login credentials",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="devid@example.com"),
     *             @OA\Property(property="password", type="string", example="password@123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully logged in",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="your_token_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication failed - Invalid email or password",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Invalid credentials.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred while user login.")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->validateCredentials($request->email, $request->password);
            $token = $this->authService->createToken($user);

            return ApiResponse::success([
                'user' => new UserResource($user),
                'token' => $token,
            ], 'User logged in successfully', Response::HTTP_OK);
        } catch (AuthenticationFailedException $e) {
            Log::error('login() User Login AuthenticationFailedException: ' . $e->getMessage());
            return ApiResponse::error($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $e) {
            Log::error('login() User Login Exception: ' . $e->getMessage());
            return ApiResponse::error('An unexpected error occurred while user login.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
