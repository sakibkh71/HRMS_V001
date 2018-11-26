<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_languages', function (Blueprint $table) {
            $table->engine ='InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('language_id')->unsigned();
            $table->string('speaking',20);
            $table->string('reading',20);
            $table->string('writing',20);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_languages',function (Blueprint $table){
            $table->dropForeign('employee_languages_user_id_foreign');
            $table->dropForeign('employee_languages_language_id_foreign');
        });
        Schema::dropIfExists('employee_languages');
    }
}
