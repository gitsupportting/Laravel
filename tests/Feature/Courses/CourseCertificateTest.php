<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\CourseAttribute;
use Tests\Feature\TestCase;

class CourseCertificateTest extends TestCase
{
    public function test_delivered_by_external()
    {
        $employee = \App\Models\User::query()->employees()->first();
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
                'created_by' => $manager->getKey(),
            ]
        );
        $course->props()->save(new CourseAttribute(['leader' => 'Test Provider']));
        $course->markComplete($employee);

        $this->get(route('courses.showEmployeeCertificate',
            [$course, $employee, 'attempt' => $course->assigneesHistory->first()->getKey()]
        ))->assertSee('This course was completed and delivered via a external provider Test Provider.');
    }

    public function test_delivered_by_care()
    {
        $employee = \App\Models\User::query()->employees()->first();
        $manager = \App\Models\User::query()->managers()->first();

        $this->actingAs($manager);

        /** @var Course $course */
        $course = \App\Models\Course::create(
            [
                'name' => 'test_external_course',
                'description' => '',
                'organization_id' => $manager->managerOrganization()->id,
                'type' => Course::TYPE_INTERNAL,
                'status' => Course::STATUS_LIVE,
                'created_by' => $manager->getKey(),
            ]
        );
        $course->props()->save(new CourseAttribute(['leader' => 'Test Provider']));
        $course->markComplete($employee);

        $this->get(route('courses.showEmployeeCertificate',
            [$course, $employee, 'attempt' => $course->assigneesHistory->first()->getKey()]
        ))->assertSeeText('This course was completed and delivered via Correct Care');
    }

    public function test_see_deleted_course_certificate()
    {
        $employee = \App\Models\User::query()->employees()->first();
        $manager = \App\Models\User::query()->managers()->first();

        $this->actingAs($manager);

        /** @var Course $course */
        $course = \App\Models\Course::create(
            [
                'name' => 'test_external_course',
                'description' => '',
                'organization_id' => $manager->managerOrganization()->id,
                'type' => Course::TYPE_INTERNAL,
                'status' => Course::STATUS_LIVE,
                'created_by' => $manager->getKey(),
            ]
        );
        $course->props()->save(new CourseAttribute(['leader' => 'Test Provider']));
        $course->markComplete($employee);
        $course->delete();

        $this->get(route('courses.showEmployeeCertificate',
            [$course, $employee, 'attempt' => $course->assigneesHistory->first()->getKey()]
        ))->assertSeeText('Certificate of Completion')->assertSeeText('test_external_course');
    }
}
