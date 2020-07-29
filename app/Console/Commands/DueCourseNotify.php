<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\CourseIsDue;
use Illuminate\Console\Command;

class DueCourseNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dueCourse:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Organization $organization */
        foreach (Organization::all() as $organization) {
            /** @var User $employee */
            foreach ($organization->employees as $employee) {
                /** @var Course $course */
                foreach ($employee->courses as $course) {
                    if($course->isDueToday($employee)) {
                        $employee->notify(new CourseIsDue($employee, $course));
                    }
                }
            }
        }
    }
}
