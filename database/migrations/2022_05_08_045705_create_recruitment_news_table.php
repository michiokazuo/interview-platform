<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('benefits');
            $table->longText('requirements')->nullable();
            $table->longText('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('salary');
            $table->string('job_position');
            $table->string('working_form');
            $table->string('gender');
            $table->string('experience');
            $table->string('workplace');
            $table->string('number_of_recruits');
            $table->unsignedBigInteger('project_id')->unsigned();
            $table->unsignedBigInteger('company_id')->unsigned();
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('cascade')->onUpdate('cascade');

            // DB::statement('CREATE FULLTEXT index recruitment_news_title_index on recruitment_news(title)');
            // DB::statement('CREATE FULLTEXT index recruitment_news_requirements_index on recruitment_news(requirements)');
            // DB::statement('CREATE FULLTEXT index recruitment_news_benefits_index on recruitment_news(benefits)');
            // DB::statement('CREATE FULLTEXT index recruitment_news_description_index on recruitment_news(description)');
            // DB::statement('CREATE FULLTEXT index recruitment_news_salary_index on recruitment_news(salary)');
            // DB::statement('CREATE FULLTEXT index recruitment_news_job_position_index on recruitment_news(job_position)');
            // DB::statement('CREATE FULLTEXT index recruitment_news_working_form_index on recruitment_news(working_form)');
            // DB::statement('CREATE FULLTEXT index recruitment_news_gender_index on recruitment_news(gender)');
            // DB::statement('CREATE FULLTEXT index recruitment_news_experience_index on recruitment_news(experience)');
            // DB::statement('CREATE FULLTEXT index recruitment_news_workplace_index on recruitment_news(workplace)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitment_news');
    }
}
