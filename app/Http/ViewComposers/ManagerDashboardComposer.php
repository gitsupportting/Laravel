<?php


namespace App\Http\ViewComposers;

use App\Models\Course;
use App\Models\JobRole;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ManagerDashboardComposer
{
    /**
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();
        $employees = $currentUser->organizationEmployees(true)
            ->with('coursesHistory', 'completedCoursesHistory')
            ->get();
        $courses = Course::forManager($currentUser)->get();
        $jobRoles = JobRole::orderBy('name')->get();
        $policies = $currentUser->managerOrganization()->policies;
        $view->with(compact(
            'employees',
            'courses',
            'jobRoles',
            'policies'
        ));
    }
}
