<?php

use App\Models\Course;
use App\Models\JobRole;
use App\Models\JobRoleCourse;
use App\Models\Policy;
use App\Models\Role;
use Illuminate\Http\UploadedFile;
use Tests\Feature\TestCase;
use Tests\Feature\WorksWithUsers;

class EmployeeImportTest extends TestCase
{
    use WorksWithUsers;

    public function test_manager_can_import_employees()
    {
        $this->actingAs($this->manager());

        $file = UploadedFile::fake()->createWithContent(
            'test_import.csv',
            <<<CSV
Firstname,lastname,email,mobile,employeeid,jobrole,accesslevel,username,password
import,test1,import1@example.com,214-123123,3,fda,admin,import_test1,secret
import,test2,import2@example.com,214-123123,3,Care Assistant,employee,import_test2,secret
import,test3,import3@example.com,214-123123,3,Senior Care Assistant,manager,import_test3,secret
import,test3,import4@example.com,214-123123,3,Care Assistant,employee,import_test4,secret
CSV
);
        $this->post(route('employees.import'), ['csvFile' => $file,]);

        $this->assertDatabaseHas('users', [
            'email' => 'import1@example.com',
            'job_role' => 1,
            'role_id' => Role::getEmployeeRole()->id,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'import2@example.com',
            'job_role' => 2,
            'role_id' => Role::getEmployeeRole()->id,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'import3@example.com',
            'job_role' => 3,
            'role_id' => Role::getManagerRole()->id,
        ]);
    }

    public function test_manager_can_import_existing_courses()
    {
        $manager = $this->manager();
        $this->actingAs($manager);
        $course = Course::create(
            [
                'name' => 'test_import_course',
                'description' => '',
                'created_by' => $manager->id
            ]
        );
        $file = UploadedFile::fake()->createWithContent(
            'test_import.csv',
            <<<CSV
 Firstname,lastname,email,mobile,employeeid,jobrole,accesslevel,username,password,CourseName,Score,ThirdPartyName,CompletionDate
 import,test1,import3@example.com,214-123123,3,1,1,import1,secret,test_import_course,80,Import Course Vendor,10/10/2020
 import,test2,import4@example.com,214-123123,3,1,1,import2,secret,Import Course 2,80,Import Course Vendor 2,10/10/2020
 import,test2,import5@example.com,214-123123,3,1,1,import3,secret,Import Course 1,100,Import Course Vendor 1,9/9/2020
CSV
        );
        $this->post(route('employees.import'), ['csvFile' => $file,]);

        $import1 = \App\Models\User::query()
            ->where('email', 'import3@example.com')->first();

        $this->assertDatabaseHas('course_assignee_history', [
            'course_id' => $course->id,
            'user_id' => $import1->id,
            'score' => 80,
            'completed_at' => now()->setDate(2020, 10, 10),
            'is_external' => true,
        ]);
    }

    public function test_manager_can_import_external_courses()
    {
        $manager = $this->manager();
        $this->actingAs($manager);
        $file = UploadedFile::fake()->createWithContent(
            'test_import.csv',
            <<<CSV
 Firstname,lastname,email,mobile,employeeid,jobrole,accesslevel,username,password,CourseName,Score,ThirdPartyName,CompletionDate
 import,test2,import7@example.com,214-123123,3,1,1,import2,secret,Import Course 2,80,Import Course Vendor 2,10/10/2020
 import,test2,import7@example.com,214-123123,3,1,1,import2,secret,Import Course 2,100,Import Course Vendor 1,9/9/2020
CSV
        );
        $this->post(route('employees.import'), ['csvFile' => $file,]);

        $import1 = \App\Models\User::query()
            ->where('email', 'import7@example.com')->first();

        $this->assertDatabaseHas('courses', [
            'name' => 'Import Course 2'
        ]);
//        $this->assertDatabaseHas('course_assignee_history', [
//            'user_id' => $import1->id,
//            'score' => 80,
//            'is_external' => true,
//        ]);
        $this->assertDatabaseHas('course_assignee_history', [
            'user_id' => $import1->id,
            'score' => 100,
            'is_external' => true,
        ]);
    }

    public function test_manager_can_import_multiple_courses()
    {
        $manager = $this->manager();
        $this->actingAs($manager);
        $file = UploadedFile::fake()->createWithContent(
            'test_import.csv',
            <<<CSV
 Firstname,lastname,email,mobile,employeeid,jobrole,accesslevel,username,password,CourseName,Score,ThirdPartyName,CompletionDate
 import,test2,import7@example.com,214-123123,3,1,1,import2,secret,Import Course 2,80,Import Course Vendor 2,10/10/2020
 import,test2,import7@example.com,214-123123,3,1,1,import2,secret,Import Course 1,100,Import Course Vendor 1,9/9/2020
CSV
        );
        $this->post(route('employees.import'), ['csvFile' => $file,]);

        $this->assertDatabaseHas('courses', [
            'name' => 'Import Course 2'
        ]);
        $this->assertDatabaseHas('courses', [
            'name' => 'Import Course 1'
        ]);
    }

    public function test_employee_courses_being_assigned_by_job_role()
    {
        $manager = $this->manager();
        $this->actingAs($manager);

        /** @var JobRole $jobRole */
        $jobRole = JobRole::query()->where('name', 'Care Assistant')->firstOrFail();
        $courses = [
            Course::create(
                [
                    'name' => 'test_import_course',
                    'description' => '',
                    'created_by' => $manager->id
                ]
            ),
            Course::create(
                [
                    'name' => 'test_import_course2',
                    'description' => '',
                    'created_by' => $manager->id
                ]
            ),
        ];
        foreach ($courses as $course) {
            JobRoleCourse::create([
                'job_role_id' => $jobRole->id,
                'organization_id' => $manager->managerOrganization()->id,
                'course_id' => $course->id
            ]);
        }

        $file = UploadedFile::fake()->createWithContent(
            'test_import.csv',
            <<<CSV
 Firstname,lastname,email,mobile,employeeid,jobrole,accesslevel,username,password
 import,test3,import4@example.com,214-123123,3,Care Assistant,employee,import_test4,secret
 import,test3,import4@example.com,214-123123,3,Care Assistant,employee,import_test4,secret
CSV
        );
        $this->post(route('employees.import'), ['csvFile' => $file,]);


        $user = \App\Models\User::query()->where('email', 'import4@example.com')->firstOrFail();

        foreach ($courses as $course) {
            $this->assertDatabaseHas('course_user', [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
            $this->assertDatabaseHas('course_assignee_history', [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
        }
    }

    public function test_employee_policies_being_assigned_by_job_role()
    {
        $manager = $this->manager();
        $this->actingAs($manager);

        /** @var JobRole $jobRole */
        $jobRole = JobRole::query()->where('name', 'Care Assistant')->firstOrFail();
        $policies = [
            Policy::create(
                [
                    'name' => 'test_import_course',
                    'description' => '',
                    'organization_id' => $manager->managerOrganization()->id,
                    'author_id' => $manager->id
                ]
            ),
            Policy::create(
                [
                    'name' => 'test_import_course2',
                    'description' => '',
                    'organization_id' => $manager->managerOrganization()->id,
                    'author_id' => $manager->id
                ]
            ),
        ];
        foreach ($policies as $policy) {
            \App\Models\JobRolePolicy::create([
                'job_role_id' => $jobRole->id,
                'organization_id' => $manager->managerOrganization()->id,
                'policy_id' => $policy->id
            ]);
        }

        $file = UploadedFile::fake()->createWithContent(
            'test_import.csv',
            <<<CSV
 Firstname,lastname,email,mobile,employeeid,jobrole,accesslevel,username,password
 import,test3,import4@example.com,214-123123,3,Care Assistant,employee,import_test4,secret
 import,test3,import4@example.com,214-123123,3,Care Assistant,employee,import_test4,secret
CSV
        );
        $this->post(route('employees.import'), ['csvFile' => $file,]);


        $user = \App\Models\User::query()->where('email', 'import4@example.com')->firstOrFail();

        foreach ($policies as $policy) {
            $this->assertDatabaseHas('policy_user', [
                'user_id' => $user->id,
                'policy_id' => $policy->id,
            ]);
        }
    }

    public function test_cant_import_more_than_capacity()
    {
        $manager = $this->manager();
        $organization =  $manager->managerOrganization();
        $organization->employees()->delete();
        $organization->update(
            [
                'license_capacity' => 2,
            ]
        );


        $file = UploadedFile::fake()->createWithContent(
            'test_import.csv',
            <<<CSV
Firstname,lastname,email,mobile,employeeid,jobrole,accesslevel,username,password
import,test1,import1@example.com,214-123123,3,fda,admin,import_test1,secret
import,test2,import2@example.com,214-123123,3,Care Assistant,employee,import_test2,secret
import,test3,import3@example.com,214-123123,3,Senior Care Assistant,manager,import_test3,secret
import,test3,import4@example.com,214-123123,3,Care Assistant,employee,import_test4,secret
CSV
        );

        $this->actingAs($manager);
        $this->post(route('employees.import'), ['csvFile' => $file,]);
        $this->assertDatabaseHas('users', [
            'email' => 'import1@example.com',
            'job_role' => 1,
            'role_id' => Role::getEmployeeRole()->id,
        ]);
        $this->assertEquals($organization->allEmployees()->count(), 2);
    }
}
