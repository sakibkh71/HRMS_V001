<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicSalaryInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_salary_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('salary_info_name', 45)->unique(); 
            $table->float('salary_info_amount', 9, 2);
            $table->boolean('salary_info_amount_status')->default(0)->comment='0=percent, 1=amount-tk';
            $table->enum('salary_info_type', ['allowance', 'deduction'])->comment='allowance add with basic, deduction minus from basic salary';
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
        Schema::dropIfExists('basic_salary_info');
    }
}
