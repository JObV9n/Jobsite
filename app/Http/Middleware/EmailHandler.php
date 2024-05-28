<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EmailHandler
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */


    public function handle(Request $request, Closure $next): Response |JsonResponse
    {
        $authUser = Auth::user();
        //check for auth user
        if (!Auth::check()) {
            return new JsonResponse([
                  'success' => false,
                'message' => 'You are not authorized to access this page.',

            ],401);
        }


        if ($authUser->email_verified_at === null) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Please verify your email before you can continue'
                ],
                401
            );
        }


        //
        return $next($request);

    }
}
