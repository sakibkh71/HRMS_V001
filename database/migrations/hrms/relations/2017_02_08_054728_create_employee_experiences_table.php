<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_experiences', function (Blueprint $table) {
            $table->engine ='InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('company_name',100);
            $table->string('position_held',100);
            $table->date('job_start_date');
            $table->date('job_end_date');
            $table->decimal('job_duration',3,2)->comment ='in year';
            $table->text('job_responsibility');
            $table->text('job_location');
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
        Schema::table('employee_experiences', function(Blueprint $table){
            $table->dropForeign('employee_experiences_user_id_foreign');
        });
        Schema::dropIfExists('employee_experiences');
    }
}
