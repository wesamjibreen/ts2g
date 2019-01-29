<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('f_name',100);
            $table->string('l_name',100);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedInteger('gender_id')->default(1);
            $table->foreign('gender_id')->references('id')->on('constants')->onUpdate('cascade')->onDelete('cascade');
            $table->date('birth_date')->nullable();
            $table->string('country',100)->nullable();
            $table->string('city',100)->nullable();
            $table->text('description')->nullable();
            $table->string('password');
            $table->unsignedInteger('profile_image_id')->nullable();
            $table->foreign('profile_image_id')->references('id')->on('images')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('cover_image_id')->nullable();
            $table->foreign('cover_image_id')->references('id')->on('images')->onUpdate('cascade')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
