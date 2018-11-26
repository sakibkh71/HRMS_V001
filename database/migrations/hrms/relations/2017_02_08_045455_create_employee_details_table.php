<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('blood_group_id')->unsigned()->default(0);
            $table->bigInteger('national_id');
            $table->string('passport_no')->nullable();
            $table->bigInteger('tin_no')->nullable();
            $table->string('father_name',50);
            $table->string('mother_name',50);
            $table->string('spouse_name',50)->nullable();
            $table->string('personal_email',100)->nullable();
            $table->string('official_email',100)->nullable();
            $table->date('birth_date')->nullable();
            $table->date('joining_date');
            $table->date('resign_date')->nullable();
            $table->string('phone_number',20)->nullable();
            $table->enum('gender',['male','female']);
            $table->enum('marital_status',['single','married','separated','other']);
            $table->integer('religion_id')->unsigned();
            $table->string('nationality',50)->nullable();
            $table->string('emergency_contact_person',100)->nullable();
            $table->string('emergency_contact_number',100)->nullable();
            $table->text('emergency_contact_address')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('religion_id')->references('id')->on('religions')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_details',function(Blueprint $table){
            $table->dropForeign('employee_details_user_id_foreign');
            $table->dropForeign('employee_details_religion_id_foreign');
        });
        Schema::dropIfExists('employee_details');
    }
}
