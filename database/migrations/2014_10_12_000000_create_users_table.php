<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('firstname', 50)->nullable();
            $table->string('middlename', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('name_ext', 5)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Others'])->default('Others')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('contact_no', 15)->nullable();
            $table->integer('user_role_id')->nullable();
            $table->boolean('status')->default(false)->nullable();
            $table->ipAddress('last_ip_address')->nullable();
            $table->text('last_browser')->nullable();
            $table->dateTime('date_login', 0)->nullable();
            $table->rememberToken();

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
        Schema::dropIfExists('users');
    }
}