<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_trainings', function (Blueprint $table) {
            $table->engine ='InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('training_code',20)->nullable();
            $table->string('training_title',200);
            $table->date('training_from_date');
            $table->date('training_to_date');
            $table->date('training_passed_date');
            $table->date('training_participation_date');
            $table->string('training_institute',200);
            $table->text('training_remarks');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_trainings',function (Blueprint $table){
            $table->dropForeign('employee_trainings_user_id_foreign');
        });
        Schema::dropIfExists('employee_trainings');
    }
}
