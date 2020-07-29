<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteLessonUsersWithoutOrganization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('lesson_user')->get()->map(function($row) {
            $user = User::find($row->user_id);
            if(!$user) {
                DB::table('lesson_user')
                    ->where('user_id', $row->user_id)
                    ->delete();
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
        //
    }
}
