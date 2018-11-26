<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('department_id')->unsigned();
            $table->integer('level_id')->unsigned();
            $table->string('designation_name',45);
            $table->text('designation_description')->nullable();
            $table->date('designation_effective_date')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('restrict');
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->dropForeign('designations_department_id_foreign');
            $table->dropForeign('designations_level_id_foreign');
        });
        Schema::dropIfExists('designations');

//        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
//        Schema::dropIfExists('designations');
//        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
