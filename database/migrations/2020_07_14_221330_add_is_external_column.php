<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsExternalColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_assignee_history', function (Blueprint $table) {
            $table->boolean('is_external')->default(false)->after('score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_assignee_history', function (Blueprint $table) {
            $table->dropColumn('is_external');
        });
    }
}
