<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'level_name', 'message', 'context',
        'loggable_id', 'loggable_type',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function loggable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
