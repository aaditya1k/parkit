<?php

namespace App\Services;

use App\Activity;
use Carbon\Carbon;

class ActivityService
{
    public function createActivity($type, $userId, $data1, $data2 = null)
    {
        if (is_array($data1)) {
            $data1 = json_encode($data1);
        }
        if (is_array($data2)) {
            $data2 = json_encode($data2);
        }

        return Activity::create([
            'type' => $type,
            'user_id' => $userId,
            'data1' => $data1,
            'data2' => $data2,
            'created_at' => Carbon::now()
        ]);
    }

    public function getActivity($userId, $pageLimit)
    {
        return $activities = Activity::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->paginate($pageLimit);
    }
}
