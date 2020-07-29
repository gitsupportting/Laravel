<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PoliciesAddAuthorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable();
            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        \App\Models\Policy::get()->each(function(\App\Models\Policy $policy) {
            $policy->author_id = $policy->organization->manager_id;
            $policy->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('author_id');
        });
    }
}
