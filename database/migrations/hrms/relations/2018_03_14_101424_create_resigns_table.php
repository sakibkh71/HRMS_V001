<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void 
     */
    public function up()
    {
        Schema::create('resigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->text('reason')->nullable();
            $table->date('effective_date');
            $table->tinyInteger('supervisor_status')->nullable()->comment='1=pending 2=forward 3=approved 4=cancel';
            $table->integer('supervisor_id')->nullable();
            $table->integer('resign_approved_by')->nullable();
            $table->date('resign_approval_date')->nullable();
            $table->tinyInteger('resign_status')->default(1)->comment='1=pending 2=forward 3=approved 4=cancel';
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
        Schema::dropIfExists('resigns');
    }
}
