<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->longText('content');
            $table->string('title')->nullable();
            $table->json('others')->nullable();
            $table->unsignedBigInteger('stack_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('root_question_id')->nullable();
            $table->integer('page_crawled')->default(1);
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('root_question_id')
                ->references('id')->on('questions')
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
        Schema::dropIfExists('questions');
    }
}
