<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAnswerSheetInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_answer_sheet_infos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('student_answer_sheet_id')->nullable();
            $table->boolean('reprint')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_answer_sheet_infos');
    }
}
