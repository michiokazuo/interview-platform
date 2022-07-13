<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInterviewExperienceBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_experience_blog', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('topics');
            $table->longText('content');
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            // DB::statement('CREATE FULLTEXT index interview_experience_blog_title_index on interview_experience_blog(title)');
            // DB::statement('CREATE FULLTEXT index interview_experience_blog_topics_index on interview_experience_blog(topics)');
            // DB::statement('CREATE FULLTEXT index interview_experience_blog_content_index on interview_experience_blog(content)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_experience_blog');
    }
}
