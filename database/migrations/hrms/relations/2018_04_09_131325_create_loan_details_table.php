<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('loan_id')->unsigned();
            $table->float('amount',10,2);
            $table->string('salary_month', 15);
            $table->date('date')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            // $table->foreign('loan_id')->references('id')->on('loans')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('loan_details',function(Blueprint $table){
        //    $table->dropForeign('loan_details_loan_id_foreign');
        // });
        
        Schema::dropIfExists('loan_details');
    }
}
