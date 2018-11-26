<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeChildrensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_childrens', function (Blueprint $table) {
            $table->engine ='InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('children_name',20);
            $table->date('children_birth_date');
            $table->enum('children_gender',['male','female']);
            $table->text('children_remarks');
            $table->string('children_education_level',100);
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
        Schema::table('employee_childrens',function (Blueprint $table){
            $table->dropForeign('employee_childrens_user_id_foreign');
        });
        Schema::dropIfExists('employee_childrens');
    }
}
