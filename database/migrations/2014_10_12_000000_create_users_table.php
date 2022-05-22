<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('address');
            $table->text('avatar');
            $table->longText('introduction')->nullable();
            $table->json('social_network')->nullable();
            $table->string('major')->nullable();
            $table->unsignedBigInteger('role_id')->unsigned();
            $table->unsignedBigInteger('candidate_id')->unsigned()->nullable();
            $table->unsignedBigInteger('company_id')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')->on('role')
                ->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('users');
    }
}
