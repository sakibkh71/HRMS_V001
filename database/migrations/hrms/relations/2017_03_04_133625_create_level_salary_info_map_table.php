<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelSalaryInfoMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_salary_info_map', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level_id')->unsigned();
            $table->integer('basic_salary_info_id')->unsigned();
            $table->float('amount', 9, 2);
            $table->timestamps();
            
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('restrict');
            $table->foreign('basic_salary_info_id')->references('id')->on('basic_salary_info')->onDelete('restrict');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_salary_info_map');
    }
}
