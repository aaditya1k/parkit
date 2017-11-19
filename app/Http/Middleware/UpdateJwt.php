<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Carbon\Carbon;
use Exception;
use Log;

class UpdateJwt
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
        $response = $next($request);
        $exp = JWTAuth::parseToken()->getPayload()->get('exp');
        try {
            $expireAt = Carbon::createFromTimestamp($exp);
            $now = Carbon::now();

            $diff = $expireAt->diffInMinutes($now, false);

            if ($diff < 0 && $diff > -60) {
                $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                JWTAuth::setToken($refreshed);
                // $user = JWTAuth::setToken($refreshed)->toUser();
                $response->headers->set('Authorization', 'Bearer ' . $refreshed);
            }
        } catch (Exception $e) {
            //
        }

        return $response;
    }
}
