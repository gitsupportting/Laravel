<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobRoleCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_role_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_role_id');
            $table->unsignedInteger('organization_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamps();

            $table->foreign('job_role_id')
                ->references('id')
                ->on('job_roles')
                ->onDelete('cascade');

            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_role_courses');
    }
}
