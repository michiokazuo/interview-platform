<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview', function (Blueprint $table) {
            $table->id();
            $table->json('record')->nullable();
            $table->json('result')->nullable();
            $table->string('address')->nullable();
            $table->string('form')->nullable();
            $table->string('room')->nullable();
            $table->datetime('time')->nullable();
            $table->boolean('is_success')->nullable();
            $table->unsignedBigInteger('candidate_id')->unsigned();
            $table->unsignedBigInteger('company_id')->unsigned()->nullable();
            $table->unsignedBigInteger('news_id')->unsigned()->nullable();
            $table->unsignedBigInteger('process_id')->unsigned()->nullable();
            $table->unsignedBigInteger('gq_test_id')->unsigned()->nullable();
            $table->unsignedBigInteger('gq_interview_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('candidate_id')
                ->references('id')->on('candidate')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('news_id')
                ->references('id')->on('recruitment_news')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('process_id')
                ->references('id')->on('recruitment_process')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('gq_test_id')
                ->references('id')->on('group_questions');
            $table->foreign('gq_interview_id')
                ->references('id')->on('group_questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview');
    }
}
