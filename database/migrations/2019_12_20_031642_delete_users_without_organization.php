<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteUsersWithoutOrganization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::get()->map(function(User $user) {
            if($user->isEmployee() && !$user->parentOrganization) {
                $user->forceDelete();

                DB::table('course_user')
                    ->where('user_id', $user->id)
                    ->delete();

                DB::table('lesson_user')
                    ->where('user_id', $user->id)
                    ->delete();
            }
            if($user->isManager() && !$user->organization) {
                $user->delete();
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
