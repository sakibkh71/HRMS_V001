<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->engine ='InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('from_supervisor_id')->unsigned()->default(0);
            $table->integer('to_supervisor_id')->unsigned()->default(0);
            $table->integer('from_branch_id')->unsigned();
            $table->integer('to_branch_id')->unsigned();
            $table->integer('from_designation_id')->unsigned();
            $table->integer('to_designation_id')->unsigned();
            $table->integer('from_unit_id')->unsigned();
            $table->integer('to_unit_id')->unsigned();
            $table->date('transfer_effective_date');
            $table->enum('promotion_type',['promotion','transfer']);
            $table->tinyInteger('promotion_status')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->foreign('from_branch_id')->references('id')->on('branches')->onDelete('restrict');
            $table->foreign('to_branch_id')->references('id')->on('branches')->onDelete('restrict');

            $table->foreign('from_designation_id')->references('id')->on('designations')->onDelete('restrict');
            $table->foreign('to_designation_id')->references('id')->on('designations')->onDelete('restrict');

            $table->foreign('from_unit_id')->references('id')->on('units')->onDelete('restrict');
            $table->foreign('to_unit_id')->references('id')->on('units')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotions',function (Blueprint $table){
            $table->dropForeign('promotions_user_id_foreign');
            $table->dropForeign('promotions_form_branch_id_foreign');
            $table->dropForeign('promotions_to_branch_id_foreign');
            $table->dropForeign('promotions_from_designation_id_foreign');
            $table->dropForeign('promotions_to_designation_id_foreign');
            $table->dropForeign('promotions_from_unit_id_foreign');
            $table->dropForeign('promotions_to_unit_id_foreign');
        });

        Schema::dropIfExists('promotions');
    }
}
