<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\JobRole;
use App\Models\JobRoleCourse;
use App\Models\JobRolePolicy;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobRoleController extends Controller
{
    public function assignCourses(Request $request, JobRole $jobRole)
    {
        $this->validate($request, [
            'courses' => 'required|array',
            'policies' => 'array',
        ]);

        $organization = Auth::user()->managerOrganization();

        JobRoleCourse::query()
            ->byJobRole($jobRole)
            ->byOrganization($organization)
            ->delete();

        JobRolePolicy::query()
            ->byJobRole($jobRole)
            ->byOrganization($organization)
            ->delete();

        $courses = $request->input('courses', []);
        foreach ($courses as $courseId) {
            JobRoleCourse::create([
                'job_role_id' => $jobRole->id,
                'organization_id' => $organization->id,
                'course_id' => $courseId
            ]);
        }
        foreach ($request->input('policies', []) as $policyId) {
            JobRolePolicy::create([
                'job_role_id' => $jobRole->id,
                'organization_id' => $organization->id,
                'policy_id' => $policyId
            ]);
        }

        /** @var User $employee */
        foreach ($jobRole->employees as $employee) {
            if($employee->organization_id != $organization->id) {
                continue;
            }
            $jobRole->addAssignee($employee);
        }

        flash('Job role ' . $jobRole->name . ' was successfully updated')->success();

        return $request->isXmlHttpRequest()
            ? response()->json([
                'type' => 'success',
                'assigned_courses' => count($courses)
            ])
            : redirect(url('/home?anchor=job-roles'));
    }
}
