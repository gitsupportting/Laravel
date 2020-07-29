<?php


namespace App\Http\ViewComposers;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmployeeDashboard
{
    /**
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $user = Auth::user();
        $courses = $user->courses()->get()->map(function(Course $course) use($user) {
            $course->dueDate = $course->calculateDueDate($course->pivot->created_at, $user->parentOrganization);
            $course->dueDate = $course->dueDate ? $course->dueDate->timestamp : now()->addYears(10)->timestamp;
            return $course;
        });
        $courses = $courses->sortBy('dueDate');

        $policies = $user->policies;

        $view->with(compact(
            'user',
            'courses',
            'policies'
        ));
    }
}
