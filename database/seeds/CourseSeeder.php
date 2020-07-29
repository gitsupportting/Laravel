<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(Faker $faker)
    {
         for($i = 0; $i < 5; $i++) {
             $course = Course::create([
                 'name' => $faker->company,
                 'description' => $faker->sentences(3, true),
                 'status' => $faker->randomElement(Course::STATUSES),
                 'created_by' => User::managers()->orderBy(DB::raw('RAND()'))->first()->id
             ]);
         }
    }
}
