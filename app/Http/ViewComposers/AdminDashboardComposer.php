<?php


namespace App\Http\ViewComposers;

use App\Models\Course;
use App\Models\Group;
use App\Models\Organization;
use App\Models\Policy;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardComposer
{
    /**
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $courses = Course::forAdmin()->get();
        $organizations = Organization::with('manager', 'employees')->get();
        $admins = User::admins()->get();
        $groups = Group::forAdmin()->get();

        $view->with(compact(
            'courses',
            'organizations',
            'admins',
            'groups'
        ));
    }
}
