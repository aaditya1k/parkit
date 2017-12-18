<?php

namespace App\Traits;

trait FormatTime
{
    // Dec, 10 2017 09:21
    public function createdAt()
    {
        if ($this->created_at != null) {
            return $this->created_at->format('d M Y H:i');
        }
    }

    // Dec, 10 2017 09:21
    public function updatedAt()
    {
        if ($this->updated_at != null) {
            return $this->updated_at->format('d M Y H:i');
        }
    }
}
