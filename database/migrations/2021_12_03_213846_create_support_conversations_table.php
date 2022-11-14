<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_conversations', function (Blueprint $table) {
            $table->id();
            $table->integer('last_chat_id')->nullable();
            $table->integer('support_id')->nullable();
            $table->integer('user_from')->nullable();
            $table->integer('user_to')->nullable();
            $table->text('chat')->nullable();
            $table->boolean('status')->default(0)->nullable();

            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('support_conversations');
    }
}