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

use Plivo\RestAPI;

class LoginController extends Controller
{
    const MOBILE_LENGTH = 10;

    /**
     * Send OTP token to a mobile no.
     */
    public function login(
        Request $request,
        UserService $userService,
        TfaCodeService $tfaCodeService
    ) {
        if (!$this->validMobileNo($request->mobile)) {
            return response()->json([
                'success' => false,
                'message' => 'Enter a valid mobile number.'
            ], 400);
        }

        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            $user = $userService->registerUser($request->mobile);
        }

        $code = $tfaCodeService->new($user->id);

        // $plivo = new RestAPI(env('PLIVO_AUTH_ID'), env('PLIVO_AUTH_TOKEN'));
        // $plivo->send_message([
        //     'src' => '+' . env('PLIVO_NO'), // Sender's phone number with country code
        //     'dst' => '+91' . $request->mobile, // Receiver's phone number with country code
        //     'text' => 'Your OTP code is ' . $code->code,
        //     'method' => 'POST'
        // ]);

        return response()->json(['success' => true]);
    }

    /**
     * Verify mobile no. and OTP of a user.
     */
    public function verify(Request $request, UserService $userService, TfaCodeService $tfaCodeService)
    {
        if (!$this->validMobileNo($request->mobile)) {
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
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Code is not valid.'
                ], 400);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Not a valid mobile number.'
        ], 400);
    }

    /**
     * Refresh JWT token.
     */
    public function refresh(Request $request)
    {
        try {
            $newToken = JWTAuth::refresh($request->token);
            return response()->json([
                'success' => true,
                'new_token' => $newToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'new_token' => $e->getMessage()
            ]);
        }
    }

    /**
     * Validate mobile number.
     * @param $mobile integer|length:10
     */
    private function validMobileNo($mobile)
    {
        return ($mobile == null || strlen($mobile) != self::MOBILE_LENGTH || !ctype_digit($mobile))
            ? false
            : true;
    }
}
