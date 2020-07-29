<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_reports', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('command', 255);
            $table->string('email_to', 255);
            $table->dateTime('execute_at');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id', 'reports_user_id_fk')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
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
        Schema::dropIfExists('email_reports');
    }
}
