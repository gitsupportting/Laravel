<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\CourseAttribute;
use Illuminate\Support\Carbon;
use Tests\Feature\TestCase;

class CoursesTest extends TestCase
{
    public function test_employee_can_see_course_details()
    {
        $employee = \App\Models\User::query()->employees()->first();
        $manager = \App\Models\User::query()->managers()->first();
        Carbon::setTestNow(now()->setDateTime(2020, 12, 1, 9, 10));

        $this->actingAs($employee);

        /** @var Course $course */
        $course = \App\Models\Course::create(
            [
                'name' => 'test_course',
                'description' => 'test_course_description',
                'organization_id' => $manager->managerOrganization()->id,
                'type' => Course::TYPE_INTERNAL,
                'status' => Course::STATUS_LIVE,
                'created_by' => $manager->getKey(),
            ]
        );
        $course->props()->save(new CourseAttribute(['start_date' => now()->toDateTimeString()]));
        $course->markComplete($employee);

        $this->get(route('course.show', $course))
            ->assertSeeText('test_course')
            ->assertSeeText('test_course_description')
            ->assertSeeText('Date and time of course 01/12/2020 09:10am');
    }
}
