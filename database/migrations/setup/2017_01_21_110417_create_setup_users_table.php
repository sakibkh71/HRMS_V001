<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetupUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table){
            $table->increments('id');
            $table->string('first_name',45);
            $table->string('last_name',45);
            $table->string('email',45)->unique();
            $table->string('password');
            $table->tinyInteger('user_type')->default('1')->comment = "1=admin,2=user";
            $table->string('mobile_number',15);
            $table->tinyInteger('status')->default('1')->comment="1=active,0=inactive";
            $table->tinyInteger('email_verify')->default('0')->comment = "1=verify,0=not verify";
            $table->rememberToken();
            $table->timestamps();
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
