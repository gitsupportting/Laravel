<?php

use App\Models\JobRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function() {

            Schema::create('job_roles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->timestamps();
            });

            $roles = [
                'Healthcare Assistant',
                'Care Assistant',
                'Senior Care Assistant',
                'Housekeeping Assistant',
                'Housekeeping Manager',
                'Domestic Assistant',
                'Laundry Assistant',
                'Care Worker',
                'Care Manager',
                'Catering Assistant',
                'Catering Manager',
                'Chef',
                'Assistant Chef',
                'Team Leader',
                'Registered Nurse',
                'Nurse',
                'Lifestyle Co-ordinator',
                'Activities Coordinator',
                'Care Coordinator',
                'Administrator',
                'Maintenance',
                'Deputy Manager',
                'Assistant Manager',
                'Area Manager',
                'Director',
                'Owner',
            ];

            foreach ($roles as $role) {
                JobRole::create(['name' => $role]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_roles');
    }
}
