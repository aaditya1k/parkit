<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Services\UserService;
use App\Services\TfaCodeService;
use Illuminate\Support\Facades\Route;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    const MOBILE_LENGTH = 10;

    public function login(
        Request $request,
        UserService $userService,
        TfaCodeService $tfaCodeService
    ) {
        if ($request->mobile == null || strlen($request->mobile) < self::MOBILE_LENGTH) {
            return response()->json([
                'success' => false,
                'message' => 'Enter a valid mobile number.'
            ], 400);
        }

        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            $user = $userService->registerUser($request->mobile);
        }

        $tfaCodeService->new($user->id);

        return response()->json(['success' => true]);
    }

    public function verify(Request $request, UserService $userService, TfaCodeService $tfaCodeService)
    {
        if ($request->mobile == null || strlen($request->mobile) < self::MOBILE_LENGTH) {
            return response()->json([
                'success' => false,
                'message' => 'Enter a valid mobile number.'
            ], 400);
        }
        if ($request->code == null || strlen($request->code) !== TfaCodeService::CODE_LENGTH) {
            return response()->json([
                'success' => false,
                'message' => 'Code is not valid.'
            ], 400);
        }

        $user = User::where('mobile', $request->mobile)->first();

        if ($user) {
            $verified = $tfaCodeService->verify($user->id, $request->code);
            if ($verified) {
                if ($user->is_active == 0) {
                    // This is user's first time, activate him.
                    $userService->activateUser($user);
                }
                try {
                    $token = JWTAuth::fromUser($user, []);
                    return response()->json(['success' => true, 'token' => $token]);
                } catch (JWTException $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong. Please try again.'
                    ], 400);
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Not a valid mobile number.'
        ], 400);
    }

    public function refresh(Request $request)
    {
        try {
            $newToken = JWTAuth::refresh($request->token);
            echo $newToken;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
