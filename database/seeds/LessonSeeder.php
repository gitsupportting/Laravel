<?php

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        foreach (Course::get() as $course) {
            for ($i = 0; $i < rand(2, 4); $i++) {
                Lesson::create([
                    'name' => $faker->company,
                    'description' => $faker->sentences(3, true),
                    'course_id' => $course->id
                ]);
            }
        }
    }
}
