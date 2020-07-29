<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PolicyUserPivot extends Pivot
{
    protected $casts = [
        'read_at' => 'datetime',
    ];
}
