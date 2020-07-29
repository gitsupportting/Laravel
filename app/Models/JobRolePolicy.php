<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class JobRolePolicy extends Model
{
    protected $table = 'job_role_policy';

    protected $fillable = ['job_role_id', 'policy_id', 'organization_id'];

    public function scopeByOrganization(Builder $builder, Organization $organization)
    {
        return $builder->where('organization_id', $organization->id);
    }

    public function scopeByJobRole(Builder $builder, JobRole $jobRole)
    {
        return $builder->where('job_role_id', $jobRole->id);
    }
}
