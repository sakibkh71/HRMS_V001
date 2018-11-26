<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdfInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('report_pdf_id')->nullable()->comment='1=salary sheet 2=bank advice 3= cash advice';
            $table->text('signatures')->nullable();
            $table->text('cover_head_text')->nullable();
            $table->text('page_header')->nullable();
            $table->text('page_footer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdf_infos');
    }
}
