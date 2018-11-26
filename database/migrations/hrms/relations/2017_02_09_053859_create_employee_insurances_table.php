<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeInsurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_insurances', function (Blueprint $table) {
            $table->engine ='InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('insurance_id')->unsigned();
            $table->date('insurance_start_date');
            $table->date('insurance_end_date');
            $table->date('insurance_duration')->comment ='in days';
            $table->decimal('premium_amount',10,2);
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
        Schema::table('employee_insurances',function (Blueprint $table){
            $table->dropForeign('employee_insurances_user_id_foreign');
        });
        Schema::dropIfExists('employee_insurances');
    }
}
