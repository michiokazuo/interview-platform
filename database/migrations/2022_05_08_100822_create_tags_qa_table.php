<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTagsQaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags_qa', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('others')->nullable();
            $table->integer('page_crawled')->default(1);
            $table->timestamps();

            DB::statement('CREATE FULLTEXT index tags_qa_name_index on tags_qa(name)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags_qa');
    }
}
