<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if(Schema::hasTable('users')){
                $table->foreign('designation_id')->references('id')->on('designations')->onDelete('restrict');
                $table->foreign('employee_type_id')->references('id')->on('employee_types')->onDelete('restrict');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('restrict');
                $table->foreign('unit_id')->references('id')->on('units')->onDelete('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_designation_id_foreign');
            $table->dropForeign('users_employee_type_id_foreign');
            $table->dropForeign('users_branch_id_foreign');
            $table->dropForeign('users_unit_id_foreign');
        });
    }
}
