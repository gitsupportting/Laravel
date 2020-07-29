<?php

namespace App\Imports;

use App\Exceptions\OrganizationCapacityOverflow;
use App\Models\Course;
use App\Models\CourseAttribute;
use App\Models\JobRole;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EmployeesImport implements OnEachRow, WithStartRow
{
    use SkipsErrors, Importable;

    private $jobRoles = null;
    private $roles = null;
    private $organization = null;
    private static $users = [];

    /**
    * @param Row $row
    * @return void
    */
    public function onRow(Row $row)
    {
        try {
            return $this->persist($row->toArray());
        } catch (\Exception $e) {
            $this->onError($e);
            return null;
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param  array $row
     * @throws OrganizationCapacityOverflow
     */
    private function persist(array $row)
    {
        $user = User::withTrashed()->where('email', $row[2])->first();
        /** @var Organization $organization */
        $organization = $this->organization();

        if (!$user) {
            $user = $organization->addEmployee(
                new User([
                    'email_verified_at' => now(),
                    'role_id' => $this->role($row[6])->getKey(),
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'email' => $row[2],
                    'phone' => $row[3],
                    'password' => Hash::make($row[8]),
                    'employee_id' => $row[4],
                    'job_role' => $this->jobRole($row[5])->getKey(),
                    'username' => $row[7],
                ])
            );
        }

        if (!$user->isManager() && $user->organization_id != $organization->getKey()) {
            return;
        }

        $this->assignJobRole($this->jobRole($row[5]), $user);

        if (isset($row[9]) && isset($row[10]) && isset($row[11]) && isset($row[12])) {
            $courseName = trim($row[9]);
            $courseScore = (float)$row[10];
            $courseLeader = trim($row[11]);
            $completeDate = Carbon::createFromFormat('d/m/Y', trim($row[12]));

            /** @var Course $course */
            $course = Course::firstOrCreate(
                [
                    'name' => $courseName,
                ],
                [
                    'name' => $courseName,
                    'description' => '',
                    'organization_id' => Auth::user()->managerOrganization()->id,
                    'type' => Course::TYPE_EXTERNAL,
                    'status' => Course::STATUS_LIVE,
                    'created_by' => Auth::user()->getKey(),
                ]
            );

            if ($course->isExternal()) {
                $course->props()->save(new CourseAttribute(['leader' => $courseLeader]));
            }

            $course->markComplete($user, $courseScore, $completeDate, true);
        }
    }
    /**
     * @param JobRole $jobRole
     * @param User $user
     * @return void
     */
    private function assignJobRole(JobRole $jobRole, User $user)
    {
        if (in_array($user->id, self::$users)) {
            return;
        }

        $jobRole->addAssignee($user);
        self::$users[] = $user->id;
    }

    /**
     * @param $name
     * @return JobRole
     */
    private function jobRole($name): JobRole
    {
        if (!$this->jobRoles) {
            $this->jobRoles = JobRole::all();
        }
        $jobRole = $this->jobRoles->where('name', trim($name))->first();
        return $jobRole ? $jobRole : $this->jobRoles->first();
    }

    /**
     * @return mixed
     */
    private function organization()
    {
        if(!$this->organization) {
            $this->organization = \Auth::user()->managerOrganization();
        }

        return $this->organization;
    }

    /**
     * @param $name
     * @return Role
     */
    private function role($name): Role
    {
        if (!$this->roles) {
            $this->roles = Role::all()->keyBy('name');
        }
        $name = trim(Str::lower($name));
        if(!in_array($name, ['manager', 'employee'])) {
            $name = 'employee';
        }
        return isset($this->roles[$name]) ? $this->roles[$name] : Role::getEmployeeRole();
    }
}
