<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('branch_name',100);
            $table->string('branch_email',100)->unique()->nullable();
            $table->string('branch_mobile',45)->nullable();
            $table->string('branch_phone',45)->nullable();
            $table->text('branch_location')->nullable();
            $table->text('branch_description')->nullable();
            $table->date('branch_effective_date')->nullable();
            $table->boolean('branch_status')->default(1)->comment='1=active, 0=inactive';
            $table->integer('created_by')->default(0);    
            $table->integer('updated_by')->default(0);
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
        Schema::dropIfExists('branches');
    }
}
