<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property Collection|Course[] $courses
 * @property Collection|Policy[] $policies
 */
class JobRole extends Model
{
    protected $fillable = ['name'];

    public function courses()
    {
        return $this
            ->belongsToMany(Course::class, 'job_role_courses')
            ->where('job_role_courses.organization_id', optional(Auth::user()->managerOrganization())->id);
    }

    public function policies()
    {
        return $this
            ->belongsToMany(Policy::class);
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'job_role');
    }

    /**
     * @param User $user
     */
    public function addAssignee(User $user)
    {
        foreach ($this->courses as $course) {
            if(!$course->isAssigned($user)) {
                $course->addAssignee($user);
            }
        }

        foreach ($this->policies as $policy) {
            if(!$policy->isAssigned($user)) {
                $policy->addAssignee($user);
            }
        }
    }
}
