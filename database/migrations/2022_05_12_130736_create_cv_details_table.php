<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCvDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_details', function (Blueprint $table) {
            $table->unsignedBigInteger('cv_id');
            $table->unsignedBigInteger('candidate_id');
            $table->string('key');
            $table->string('value');
            $table->string('type');
            $table->timestamps();
            
            $table->foreign('cv_id')
                ->references('id')->on('cv')
                ->onDelete('cascade');
            
            $table->foreign('candidate_id')
                ->references('id')->on('candidate')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cv_details');
    }
}
