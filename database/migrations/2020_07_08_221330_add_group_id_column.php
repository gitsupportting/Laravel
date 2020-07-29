<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_reports', function (Blueprint $table) {
            $table->dropForeign('reports_user_id_fk');
            $table->dropColumn('user_id');
            $table->bigInteger('group_id', false, true)->nullable();
            $table->foreign('group_id', 'email_reports_group_id_fk')
                ->references('id')
                ->on('groups')
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
        Schema::table('email_reports', function (Blueprint $table) {
            $table->dropForeign('email_reports_group_id_fk');
            $table->dropColumn('group_id');
        });
    }
}
