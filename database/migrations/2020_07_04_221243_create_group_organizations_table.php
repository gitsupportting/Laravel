<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_organizations', function (Blueprint $table) {
            $table->integer('organization_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->foreign('organization_id', 'group_organization_organization_id_fk')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');
            $table->foreign('group_id', 'group_organization_group_id_fk')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');
            $table->unique(['organization_id', 'group_id'], 'group_organization_unique_idx');
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
        Schema::dropIfExists('group_organizations');
    }
}
