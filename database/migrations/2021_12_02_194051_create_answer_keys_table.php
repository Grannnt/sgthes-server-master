<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_keys', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('answer_key_name')->nullable();;
            $table->integer('school_year_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->string('choices_type', 50)->nullable();
            $table->string('semester')->nullable();
            $table->integer('number_of_question')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_keys');
    }
}
