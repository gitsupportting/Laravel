<?php


namespace App\Http\Controllers\Manager;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSettings;
use App\Models\User;
use App\Views\CourseView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function assign(Course $course)
    {
        $employees = Auth::user()->organizationEmployees()
            ->get();
        return view('manager.courses.assign', compact('course', 'employees'));
    }

    public function storeAssignments(Request $request, Course $course)
    {
        $this->validate($request, [
            'user_id' => 'array'
        ]);

        $assignees = User::whereIn('id', $request->input('user_id'))->get();
        $assignees->each(function(User $user) use($course) {
            $course->addAssignee($user);
        });

        flash('User were successfully assigned to the course "'.$course->name.'"')->success();

        return redirect(route('home', ['anchor' => 'courses']));
    }

    public function showEmployeeCertificate($courseId, $employeeId)
    {
        /** @var User $user */
        $user = User::findOrFail($employeeId);

        $this->authorize('view', $user);

        /** @var Course $course */
        $course = $user->completedCoursesHistory()->findOrFail($courseId);

        return view('manager.courses.employeeCertificate', compact('course', 'user'));
    }

    public function settings(Course $course)
    {
        $courseSettings = $course->settings()
            ->where('organization_id', Auth::user()->managerOrganization()->id)
            ->first();
        if(!$courseSettings) {
            $courseSettings = new CourseSettings();
            $courseSettings->forceFill([
                'settings' => [
                    'score' => 80,
                    'retake_month' => 0,
                    'due_re_enable' => 0,
                    'due_notify_employee' => 1
                ]
            ]);
        }

        return view('manager.courses.settings', compact('course', 'courseSettings'));
    }

    public function storeSettings(Request $request, Course $course)
    {
        $settings = $request->input('settings');
        $this->validate($request, [
            'settings.due_re_enable' => 'lte:' . (int)@$settings['retake_month'],
        ], [
            'settings.due_re_enable.lte' => 'The "Re-enable the course before it\'s due" must be less than or equal '.(int)@$settings['retake_month'].'.'
        ]);
        $courseSettings = $course->settings()
            ->where('organization_id', Auth::user()->managerOrganization()->id)
            ->firstOrNew([]);
        $courseSettings->forceFill([
            'course_id' => $course->id,
            'organization_id' => Auth::user()->managerOrganization()->id,
            'settings' => $request->input('settings')
        ]);
        $courseSettings->save();

        flash('Course settings were successfully saved')->success();

        return redirect(route('home', ['anchor' => 'courses']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('admin.courses.createExternal', [
            'course' => new Course()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required|date_format:d/m/Y H:i',
        ]);

        $data['created_by'] = Auth::id();
        $data['status'] = Course::STATUS_LIVE;
        $data['type'] = Course::TYPE_EXTERNAL;
        $data['organization_id'] = Auth::user()->managerOrganization()->id;

        $course = Course::create(array_merge($data, $request->only('duration', 'subjects', 'time_to_complete')));
        $props = $request->only('leader', 'phone', 'type', 'start_date', 'duration');
        $props['start_date'] = Carbon::createFromFormat('d/m/Y H:i', $props['start_date']);
        $course->props()->create($props);

        flash('External Course was successfully created')->success();

        return redirect(route('home', ['anchor' => 'courses']));
    }

    /**
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Course $course)
    {
        abort_if(!$course->isExternal() || $course->organization_id != auth()->user()->managerOrganization()->id, Response::HTTP_NOT_FOUND);

        return  view('admin.courses.editExternal', [
            'course' => $course,
        ]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Course $course)
    {
        abort_if(!$course->isExternal() || $course->organization_id != auth()->user()->managerOrganization()->id, Response::HTTP_NOT_FOUND);

        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required|date_format:d/m/Y H:i',
        ]);

        $props = $request->only('leader', 'phone', 'type', 'start_date', 'duration');
        $props['start_date'] = Carbon::createFromFormat('d/m/Y H:i', $props['start_date']);
        $course->update(array_merge($data, $request->only('duration', 'subjects', 'time_to_complete')));
        $course->props->update($props);

        flash('External Course was successfully updated')->success();

        return redirect(route('home', ['anchor' => 'courses']));
    }

    /**
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Course $course)
    {
        abort_if(!$course->isExternal() || $course->organization_id != auth()->user()->managerOrganization()->id, Response::HTTP_NOT_FOUND);
        $course->delete();
        return redirect()->route('home', ['anchor' => 'courses']);
    }
}
