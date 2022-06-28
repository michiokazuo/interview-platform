<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupHasQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_has_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('group_id');

            $table->foreign('question_id')
                ->references('id')->on('questions')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('group_id')
                ->references('id')->on('group_questions')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_has_questions');
    }
}
