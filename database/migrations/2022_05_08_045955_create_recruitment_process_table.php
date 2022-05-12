<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_process', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->unsignedBigInteger('project_id')->unsigned();
            $table->unsignedBigInteger('prev_step')->unsigned()->nullable();
            $table->unsignedBigInteger('next_step')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('prev_step')
                ->references('id')->on('recruitment_process');
            $table->foreign('next_step')
                ->references('id')->on('recruitment_process');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitment_process');
    }
}
