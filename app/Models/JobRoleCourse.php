<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class JobRoleCourse extends Model
{
    protected $fillable = [
        'job_role_id',
        'organization_id',
        'course_id',
    ];

    public function scopeByOrganization(Builder $builder, Organization $organization)
    {
        return $builder->where('organization_id', $organization->id);
    }

    public function scopeByJobRole(Builder $builder, JobRole $jobRole)
    {
        return $builder->where('job_role_id', $jobRole->id);
    }
}
