<?php

namespace App\Services;

use App\User;

class BalanceService
{
    private $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function addBalance($amount, $userId, $method)
    {
        User::where('id', $userId)->increment('balance', $amount);
        $this->activityService->create(
            ActivityService::ACTIVITY_BALANCE_ADD,
            $userId,
            $amount,
            ['method' => $method]
        );
        return true;
    }
}
