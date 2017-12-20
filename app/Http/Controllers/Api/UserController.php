<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ActivityService;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function balance(Request $request)
    {
        return response()->json(['success' => true, 'balance' => $request->user()->balance]);
    }

    public function activity(Request $request, ActivityService $activityService)
    {
        $activites = $activityService->getActivity($request->user()->id, 20);
        return response()->json([
            'success' => true,
            'activities' => $activites
        ]);
    }
}
