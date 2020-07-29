<?php

namespace App\Http\Controllers\Manager;

use App\Models\Course;
use App\Models\CourseSettings;
use App\Models\Organization;
use App\Models\User;
use App\Reports\ViewReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use function foo\func;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $report = $request->get('report', 'trainingMatrix');

        if (request()->has('download')) {
            return Excel::download(
                new ViewReport($this->{$report}('components.reports.print')),
                $report.date('d-m-Y').'.xlsx'
            );
        }

        if (request()->has('print')) {
            return view('manager.reports.print', [
                'report' => $this->{$report}('components.reports.print')->render()
            ]);
        }

        return view('manager.reports.index', [
            'report' => $this->{$report}('components.reports')->render(),
        ]);
    }

    public function trainingMatrix($namespace)
    {
        $organization = \Auth::user()->managerOrganization();
        list($employees, $courses) = $this->organizationCompletedCourses($organization);

        $reportName = 'Training Matrix';
        $employeeCourses = 'coursesHistory';

        return view("$namespace.trainingMatrix", compact(
            'reportName',
            'organization',
            'courses',
            'employees',
            'employeeCourses'
        ));
    }

    public function due($namespace)
    {
        $organization = \Auth::user()->managerOrganization();
        list($employees) = $this->organizationCompletedCourses($organization);

        $reportName = 'Due Soon';
        $courses = collect([]);
        $employees = $employees->filter(function($employee) use($courses) {
            $employee->coursesHistory = $employee->coursesHistory->filter(
                function (Course $course) use ($employee, $courses) {
                    if($course->isDueSoon($employee)) {
                        $courses->push($course);
                        return true;
                    }
                    return false;
                });

            return $employee->coursesHistory->isNotEmpty();
        });
        $courses = $courses->unique('id')->sort(function (Course $course1, Course $course2) {
            return $course1->name <=> $course2->name;
        });
        $employeeCourses = 'coursesHistory';

        return view("$namespace.trainingMatrix", compact(
            'reportName',
            'organization',
            'courses',
            'employees',
            'employeeCourses'
        ));
    }

    public function overdue($namespace)
    {
        $organization = \Auth::user()->managerOrganization();
        list($employees) = $this->organizationCompletedCourses($organization);

        $reportName = 'Overdue';
        $courses = collect([]);
        $employees = $employees->filter(function($employee) use($courses) {
            $employee->coursesHistory = $employee->coursesHistory->filter(
                function (Course $course) use ($employee, $courses) {
                    if($course->isOverdue($employee)) {
                        $courses->push($course);
                        return true;
                    }
                    return false;
                });

            return $employee->coursesHistory->isNotEmpty();
        });
        $courses = $courses->unique('id')->sort(function (Course $course1, Course $course2) {
            return $course1->name <=> $course2->name;
        });
        $employeeCourses = 'coursesHistory';

        return view("$namespace.trainingMatrix", compact(
            'reportName',
            'organization',
            'courses',
            'employees',
            'employeeCourses'
        ));
    }

    public function nonCompliant($namespace)
    {
        $organization = \Auth::user()->managerOrganization();
        list($employees, $courses, $coursesSettings) = $this->organizationCompletedCourses($organization);

        $metCourses = [];
        $employees = $employees->filter(function($employee) use($coursesSettings, &$metCourses) {
            if(!$employee->completedCoursesHistory->count()) {
                return false;
            }

            $hasMetCourses = false;
            foreach($employee->completedCoursesHistory->groupBy('course_id') as $courseHistory) {
                $courseHistory = $courseHistory->first();
                $settings = isset($coursesSettings[$courseHistory->id]) ? $coursesSettings[$courseHistory->id] : null;
                if($settings && $courseHistory->pivot->score < @$settings->settings['score']) {
                    $metCourses[] = $courseHistory->id;
                    $hasMetCourses = true;
                }
            }

            return $hasMetCourses;
        });
        $courses = $courses->filter(function($course) use($metCourses) {
            return in_array($course->id, $metCourses);
        });

        $employeeCourses = 'completedCoursesHistory';
        $reportName = 'Non Compliant';

        return view("$namespace.trainingMatrix", compact(
            'reportName',
            'organization',
            'courses',
            'employees',
            'employeeCourses'
        ));
    }

    public function incomplete($namespace)
    {
        $organization = \Auth::user()->managerOrganization();
        $employees = $organization->manager->organizationEmployees(true)
            ->with('incompleteCourses')
            ->get();

        $reportName = 'Incomplete';
        $courses = collect([]);
        $employees = $employees->filter(function($employee) use (&$courses) {
            $courses = $courses->merge($employee->incompleteCourses);
            return $employee->incompleteCourses->isNotEmpty();
        });
        $courses = $courses->unique('id');

        $employeeCourses = 'incompleteCourses';

        return view("$namespace.trainingMatrix", compact(
            'reportName',
            'organization',
            'courses',
            'employees',
            'employeeCourses'
        ));
    }

    public function policy($namespace)
    {
        $policies = collect([]);
        $organization = \Auth::user()->managerOrganization();
        $employees = $organization->manager->organizationEmployees(true)
            ->with('policies')->get();

        foreach($employees as $employee) {
            $policies = $policies->merge($employee->policies);
        }
        $policies = $policies->unique('id');

        $reportName = 'Policy Read & Not Read';

        return view("$namespace.policyMatrix", compact(
            'reportName',
            'organization',
            'policies',
            'employees'
        ));
    }

    private function organizationCompletedCourses(Organization $organization): array
    {
        $courses = collect([]);
        $employees = $organization->manager->organizationEmployees(true)
            ->with('courses.settings', 'coursesHistory', 'coursesHistory.settings')->get();
        foreach ($employees as $employee) {
            $courses = $courses->merge($employee->courses);
        }
        $courses = $courses->unique('id')->sort(function (Course $course1, Course $course2) {
            return $course1->name <=> $course2->name;
        });
        $coursesSettings = CourseSettings::query()
            ->whereIn('course_id', $courses->pluck('id'))
            ->where('organization_id', $organization->id)
            ->get()
            ->keyBy('course_id');

        return [$employees, $courses, $coursesSettings];
    }
}
