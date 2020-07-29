<?php

namespace App\Http\ViewComposers;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class EmployeesAssignCourseComposer
{
    /**
     * @var Collection
     */
    static private $courses;

    /**
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        if (!static::$courses) {
            static::$courses = Course::with('assignees', 'graduates')->active()->get();
        }
        $view->with(['courses' => static::$courses]);
    }
}
