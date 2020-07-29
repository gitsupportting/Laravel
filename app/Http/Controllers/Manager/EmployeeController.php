<?php


namespace App\Http\Controllers\Manager;


use App\Exceptions\OrganizationCapacityOverflow;
use App\Http\Controllers\Controller;
use App\Imports\EmployeesImport;
use App\Models\Course;
use App\Models\JobRole;
use App\Models\Role;
use App\Models\User;
use App\Views\CourseView;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $employee = new User();
        $roles = Role::managerManaged()->get();
        $password = Str::random(8);

        return view('manager.employees.create', compact('employee', 'roles', 'password'));
    }

    public function store(Request $request)
    {
        /** pre validate */
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'on_leave' => 'required|in:0,1',
            'job_role' => 'required',
            'role_id' => [Rule::in([Role::getManagerRole()->getKey(), Role::getEmployeeRole()->getKey()])]
        ]);

        if(empty($request->input('username'))) {
            $username = User::generateUsername($request->input('first_name'), $request->input('last_name'));
            $request = $request->merge(['username' => $username]);
        }

        $rules = [
            'username' => 'required|unique:users,username',
            'password' => 'required',
        ];

        if(!empty($request->input('date_of_birth'))) {
            $rules['date_of_birth'] = 'date_format:d-m-Y';
        }

        if(!empty($request->input('email'))) {
            $rules['email'] = 'required|email|unique:users,email';
        } else {
            $request->merge(['email' => 'generic'.microtime() . '@example.com']);
        }

        $this->validate($request, $rules);

        $data = $request->input();
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = now();
        if(!empty($data['date_of_birth'])) {
            $data['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $data['date_of_birth']);
        }

        $user = $request->user()->managerOrganization()->addEmployee(new User($data));

        if($user->isEmployee()) {
            foreach ($user->jobRole->courses as $course) {
                $course->addAssignee($user);
            }
            foreach ($user->jobRole->policies as $policy) {
                $policy->addAssignee($user);
            }
        }

        flash('Employee was successfully created')->success();

        return redirect(route('home'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $employee = \Auth::user()->organizationEmployees(true)
            ->withTrashed()
            ->where('id', $id)
            ->firstOrFail();
        $roles = Role::managerManaged()->get();

        $coursesViews = [];
        foreach ($employee->coursesHistory as $course) {
            $coursesViews[] = new CourseView($course, $employee);
        }

        $password = '';

        return view('manager.employees.edit', compact('employee', 'roles', 'coursesViews', 'password'));
    }

    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user()->organizationEmployees(true)->where('id', $id)->firstOrFail();

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'role_id' => ['required', Rule::in([Role::getManagerRole()->getKey(), Role::getEmployeeRole()->getKey()])],
            'email' => ['required', 'email', Rule::unique(User::class, 'email')->ignoreModel($user)]
        ]);

        $data = $request->input();

        if(!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }


        $role = Role::findOrFail($data['role_id']);
        $request->user()->managerOrganization()->changeEmployeeRole($user, $role);

        if(isset($role) && $role->isEmployee() && !empty($data['job_role'])) {
            /** @var JobRole $jobRole */
            $jobRole = JobRole::findOrFail($data['job_role']);
            $jobRole->addAssignee($user);
        } elseif(!empty($user->joRole)) {
            foreach ($user->jobRole->policies as $policy) {
                $policy->addAssignee($user);
            }
        }

        $user->update($data);
        flash('Employee was successfully updated')->success();

        return redirect(route('home'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bulkArchive(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|array'
        ], [
            'user_id.required' => 'No employees selected.'
        ]);

        \Auth::user()
            ->organizationEmployees()
            ->whereIn('id', $request->input('user_id'))
            ->delete();

        flash('Selected employee(s) were successfully archived')->success();

        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bulkRestore(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|array'
        ], [
            'user_id.required' => 'No employees selected.'
        ]);

        $request->user()->managerOrganization()->restoreEmployees($request->input('user_id'));
        flash('Selected employee(s) were successfully restored')->success();

        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bulkDelete(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|array'
        ], [
            'user_id.required' => 'No employees selected.'
        ]);

        \Auth::user()
            ->organizationEmployees()
            ->whereIn('id', $request->input('user_id'))
            ->forceDelete();

        flash('Selected employee(s) were successfully deleted')->success();

        return back();
    }

    public function viewArchive()
    {
        $employees = \Auth::user()
            ->organizationEmployees()
            ->onlyTrashed()
            ->get();
        return view('manager.employees.archive', compact('employees'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'csvFile' => 'required|file'
        ]);


         $importer = new EmployeesImport;
         $importer->import($request->file('csvFile')->path(), null, \Maatwebsite\Excel\Excel::CSV);

         if ($importer->errors()->isNotEmpty() && $error = $importer->errors()->first()) {
             if ($error instanceof OrganizationCapacityOverflow) {
                 flash($error->getMessage())->error();
                 return back();
             }
             \Log::warning('EmployeesImport warnings', ['e' => $error]);
         }

        flash('Employees were successfully imported')->success();

        return back();
    }

    public function bulkAssign(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|array',
            'course_id' => 'required'
        ]);

        /** @var Course $course */
        $courseId = $request->input('course_id');
        $assignees = User::whereIn('id', $request->input('user_id'))->get();
        $alreadyAssigned = 0;
        $assigned = 0;
        $assignees->each(function(User $user) use($courseId, &$alreadyAssigned, &$assigned) {
            $course = $user->courses()->where('course_id', $courseId)->first();
            if($course && !$course->isCompletedBy($user)) {
                $alreadyAssigned++;
            } else {
                $assigned++;
                $course = Course::findOrFail($courseId);
                $course->addAssignee($user);
            }
        });
        $course = Course::findOrFail($courseId);

        $message = "{$assigned} users added to the course ‘{$course->name}’" . ($alreadyAssigned > 0 ? ", {$alreadyAssigned} users were already assigned the course." : '.');

        flash($message)->success();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function bulkMarkCompleted(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        /** @var Course $course */
        $course = Course::byOrganization($user)->findOrFail($request->input('course_id'));
        /** @var Collection $assignees */
        $assignees = User::byEmployeeOrganization($user->managerOrganization(), User::WITH_MANAGER)
            ->whereIn('id', $request->input('user_id'))->get();

        $assignees->each(function(User $user) use($course) {
            $course->markComplete($user, 100, now(), true);
        });

        flash()->success("{$assignees->count()} users marked the course ‘{$course->name}’ as completed");

        return response()->json();
    }
}
