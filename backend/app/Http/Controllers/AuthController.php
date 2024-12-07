<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\TokenService;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRegisterRequest;
use App\Interfaces\AuthRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        protected TokenService $tokenService,
        protected AuthRepositoryInterface $authRepository
    ) {}

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
