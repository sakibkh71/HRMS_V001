<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsTable extends Migration
{
    
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('unit_name',100);
            $table->integer('unit_parent_id')->default(0)->unsigned();
            $table->integer('unit_departments_id')->unsigned();
            $table->text('unit_details')->nullable();
            $table->date('unit_effective_date')->nullable();
            $table->boolean('unit_status')->default(1)->comment='1=active, 0=inactive';
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
