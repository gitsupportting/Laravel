<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use Tests\Feature\TestCase;

class ExternalCoursesTest extends TestCase
{
    public function test_manager_can_destroy_external_course()
    {
        $manager = \App\Models\User::query()->managers()->first();

        $this->actingAs($manager);

        $course = \App\Models\Course::create(
            [
                'name' => 'test_external_course',
                'description' => '',
                'organization_id' => $manager->managerOrganization()->id,
                'type' => Course::TYPE_EXTERNAL,
                'status' => Course::STATUS_LIVE,
                'created_by' => $manager->getKey(),
            ]
        );

        $this->get(route('home', $course))->assertOk()->assertSee('test_external');
        $this->get(route('courses.editExternal', $course))->assertOk()->assertSee('Delete');
        $this->delete(route('courses.destroyExternal', $course))->assertRedirect();
    }

    public function test_employee_cant_see_deleted_external_course()
    {
        $employee = \App\Models\User::query()->employees()->first();
        $manager = \App\Models\User::query()->managers()->first();

        $this->actingAs($employee);

        /** @var Course $course */
        $course = \App\Models\Course::create(
            [
                'name' => 'test_external_course',
                'description' => '',
                'organization_id' => $manager->managerOrganization()->id,
                'type' => Course::TYPE_EXTERNAL,
                'status' => Course::STATUS_LIVE,
                'start_date' => now(),
                'created_by' => $manager->getKey(),
            ]
        );
        $course->assignees()->attach($employee->getKey());
        $course->assigneesHistory()->attach($employee->getKey());

        $deletedCourse = \App\Models\Course::create(
            [
                'name' => 'test_external_course_del',
                'description' => '',
                'organization_id' => $manager->managerOrganization()->id,
                'type' => Course::TYPE_EXTERNAL,
                'status' => Course::STATUS_LIVE,
                'created_by' => $manager->getKey(),
            ]
        );
        $deletedCourse->assignees()->attach($employee->getKey());
        $course->assigneesHistory()->attach($employee->getKey());
        $deletedCourse->delete();

        $this->get(route('home', $course))->assertOk()
        ->assertSeeText('test_external_course')
        ->assertDontSeeText('test_external_course_del');
    }

    public function test_see_deleted_course_in_user_history()
    {
        $manager = \App\Models\User::query()->managers()->first();

        $this->actingAs($manager);

        /** @var Course $course */
        $course = \App\Models\Course::create(
            [
                'name' => 'test_external_course',
                'description' => '',
                'organization_id' => $manager->managerOrganization()->id,
                'type' => Course::TYPE_EXTERNAL,
                'status' => Course::STATUS_LIVE,
                'start_date' => now(),
                'created_by' => $manager->getKey(),
            ]
        );
        $course->assignees()->attach($manager->getKey());
        $course->assigneesHistory()->attach($manager->getKey());
        $course->delete();

        $this->get(route('employees.edit', $manager))
            ->assertSeeText('test_external_course');
    }

    public function test_user_can_mark_as_complete()
    {
        $employee = \App\Models\User::query()->employees()->first();
        $manager = \App\Models\User::query()->managers()->first();

        $this->actingAs($employee);

        /** @var Course $course */
        $course = \App\Models\Course::create(
            [
                'name' => 'test_external_uncompleted_course',
                'description' => '',
                'organization_id' => $manager->managerOrganization()->id,
                'type' => Course::TYPE_EXTERNAL,
                'status' => Course::STATUS_LIVE,
                'start_date' => now(),
                'created_by' => $manager->getKey(),
            ]
        );
        $course->assignees()->attach($employee->getKey());
        $course->assigneesHistory()->attach($employee->getKey());

        $this->get(route('course.show', $course))->assertSeeText('Mark course as complete');
        $this->post(route('course.markCompleteExternal', $course))->assertRedirect();
        $this->get('home')->assertSee('Course "test_external_uncompleted_course" was marked as complete');

    }
}
