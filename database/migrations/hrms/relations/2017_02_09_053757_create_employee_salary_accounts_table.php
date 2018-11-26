<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSalaryAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salary_accounts', function (Blueprint $table) {
            $table->engine ='InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('bank_id')->unsigned();
            $table->string('bank_account_no',50);
            $table->string('bank_account_name',100);
            $table->string('bank_branch_name',100);
            $table->text('bank_branch_address');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_salary_accounts',function (Blueprint $table){
            $table->dropForeign('employee_salary_accounts_user_id_foreign');
            $table->dropForeign('employee_salary_accounts_bank_id_foreign');
        });
        Schema::dropIfExists('employee_salary_accounts');
    }
}
