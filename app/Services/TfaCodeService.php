<?php

namespace App\Services;

use App\TfaCode;
use Carbon\Carbon;

class TfaCodeService
{
    const CODE_LENGTH = 6;

    public function new($userId)
    {
        return TfaCode::create([
            'user_id' => $userId,
            'code' => strtoupper(str_random(self::CODE_LENGTH)),
            'created_at' => Carbon::now()
        ]);
    }

    public function verify($userId, $code)
    {
        $tfacode = TfaCode::where('user_id', $userId)
            ->where('code', $code)
            ->first();
        if ($tfacode) {
            $tfacode->delete();
            return true;
        }
        return false;
    }
}
