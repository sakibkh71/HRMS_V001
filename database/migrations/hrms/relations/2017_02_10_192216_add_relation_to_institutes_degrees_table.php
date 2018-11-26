<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationToInstitutesDegreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutes',function(Blueprint $table){
            if(Schema::hasTable('institutes')){
                $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict');
            }
        });

        Schema::table('degrees',function(Blueprint $table){
            if(Schema::hasTable('degrees')){
                $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict');
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
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropForeign('institutes_education_level_id_foreign');
        });

        Schema::table('degrees', function (Blueprint $table) {
            $table->dropForeign('degrees_education_level_id_foreign');
        });
    }
}
