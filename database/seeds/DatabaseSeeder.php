<?php

use App\Models\Course;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(LessonSeeder::class);
        $this->call(SlideSeeder::class);
    }
}
