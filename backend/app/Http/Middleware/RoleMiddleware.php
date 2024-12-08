<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {   
        if (!Auth::user()->roles->contains('name', $role)) {
            return ApiResponse::error('Unauthorized access.', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
