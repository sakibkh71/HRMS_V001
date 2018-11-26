<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_educations', function (Blueprint $table) {
            $table->engine ='InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('institute_id')->unsigned();
            $table->integer('degree_id')->unsigned();
            $table->string('achievement',200)->nullable();
            $table->boolean('foreign_degree')->default(0)->comment='0=no, 1=yes';
            $table->string('pass_year',4);
            $table->enum('result_type',['cgpa','division']);
            $table->float('cgpa',3,2)->nullable();
            $table->tinyInteger('division')->nullable();
            $table->string('certificate')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('institute_id')->references('id')->on('institutes')->onDelete('restrict');
            $table->foreign('degree_id')->references('id')->on('degrees')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_educations',function(Blueprint $table){
           $table->dropForeign('employee_educations_user_id_foreign');
           $table->dropForeign('employee_educations_institute_id_foreign');
           $table->dropForeign('employee_educations_degree_id_foreign');
        });
        Schema::dropIfExists('employee_educations');
    }
}
