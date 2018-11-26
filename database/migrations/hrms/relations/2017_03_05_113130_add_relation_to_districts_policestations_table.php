<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationToDistrictsPolicestationsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('districts',function(Blueprint $table){
            if(Schema::hasTable('districts')){
                $table->foreign('division_id')->references('id')->on('divisions')->onDelete('restrict');
            }
        });
        Schema::table('police_stations',function(Blueprint $table){
            if(Schema::hasTable('police_stations')){
                $table->foreign('district_id')->references('id')->on('districts')->onDelete('restrict');
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
        Schema::table('districts', function (Blueprint $table) {
            $table->dropForeign('districts_division_id_foreign');
        });
        Schema::table('police_stations', function (Blueprint $table) {
            $table->dropForeign('police_stations_education_district_id_foreign');
        });
    }
}
