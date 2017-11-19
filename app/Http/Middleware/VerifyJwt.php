<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Middleware\GetUserFromToken;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use JWTAuth;

class VerifyJwt extends GetUserFromToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return response()->json([
                'success' => false,
                'message' => 'token_not_provided'
            ], 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'token_expired'
            ], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'token_invalid'
            ], $e->getStatusCode());
        }

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'user_not_found'
            ], 404);
        }

        return $next($request);
    }
}
