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
            $table->string('record');
            $table->json('result');
            $table->string('address')->nullable();
            $table->string('form');
            $table->string('time');
            $table->unsignedBigInteger('candidate_id')->unsigned();
            $table->unsignedBigInteger('company_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('candidate_id')
                ->references('id')->on('candidate')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')
                ->references('id')->on('company')
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
        Schema::dropIfExists('interview');
    }
}
