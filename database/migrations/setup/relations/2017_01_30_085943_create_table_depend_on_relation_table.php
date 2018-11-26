<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDependOnRelationTable extends Migration
{
    
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('parent_id')->unsigned();
            $table->string('company_code',20)->nullable();
            $table->string('company_name',45);
            $table->text('company_address');
            $table->string('database_name',45)->unique();
            $table->date('package_end_date');
            $table->tinyInteger('config_status')->default('1')->comment="1=company active,0=company inactive";
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('user_emails', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('config_id')->unsigned();
            $table->string('email',75)->unique();
            $table->timestamps();

            $table->foreign('config_id')->references('id')->on('configs')->onDelete('cascade');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('config_id')->unsigned();
            $table->integer('package_id')->unsigned();
            $table->double('payment_amount', 11, 2);
            $table->smallInteger('payment_duration');
            $table->tinyInteger('payment_status')->default('1')->comment="1=active,0=inactive";
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('config_id')->references('id')->on('configs')->onDelete('cascade');
        });

        Schema::create('module_package_maps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->unsigned()->nullable();
            $table->integer('package_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('module_package_maps');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('user_emails');
        Schema::dropIfExists('configs');
    }
}
