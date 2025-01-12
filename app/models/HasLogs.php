<?php

namespace App\Models;

use App\Models\Log;

trait HasLogs
{
    public function logs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }
}
