<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_managers', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->foreign('user_id', 'group_manager_user_id_fk')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('group_id', 'group_manager_group_id_fk')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');
            $table->unique(['user_id', 'group_id'], 'group_manager_unique_idx');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_managers');
    }
}
