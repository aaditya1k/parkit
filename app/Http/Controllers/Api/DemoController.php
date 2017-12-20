<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BalanceService;
use App\Parking;

class DemoController extends Controller
{
    /**
     * Add balance to logged in user.
     */
    public function addBalance(Request $request, BalanceService $balanceService)
    {
        $amount = $request->amount;
        $balanceService->addBalance($amount, $request->user()->id, 'demo');
        return response()->json(['success' => true]);
    }
}
