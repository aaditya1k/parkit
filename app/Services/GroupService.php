<?php

namespace App\Services;

use App\Group;

class GroupService
{
    public function getAll($limit = 20)
    {
        return Group::select('id', 'name')
            ->orderBy('name', 'asc')
            ->get()
            ->pluck('name', 'id');
    }
}
