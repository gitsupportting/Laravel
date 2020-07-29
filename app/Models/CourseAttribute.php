<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAttribute extends Model
{
    protected $fillable = ['course_id', 'leader', 'phone', 'type', 'start_date', 'duration'];

    const TYPES = [
        'One to One',
        'Group',
        'Internal Course',
        'External Course',
        'Conference',
        'Other',
    ];

    protected $casts = ['start_date' => 'datetime'];
}
