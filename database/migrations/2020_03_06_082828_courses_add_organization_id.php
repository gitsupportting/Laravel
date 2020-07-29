<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoursesAddOrganizationId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::transaction(function () {
            Schema::table('courses', function (Blueprint $table) {
                $table->unsignedInteger('organization_id')->nullable();

                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->onDelete('cascade');
            });

            foreach (\App\Models\Course::whereType(\App\Models\Course::TYPE_EXTERNAL)->get() as $course) {
                $course->organization_id = $course->author->managerOrganization()->id;
                $course->save();
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
        Schema::table('courses', function (Blueprint $table) {
            //
        });
    }
}
