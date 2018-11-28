<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_profile')) {
            Schema::create('user_profile', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->string('name', 30)->nullable();
                $table->string('staff_code')->nullable()->comment('員工編號');
                $table->string('phone_number', 20)->nullable();
                $table->string('title', 50)->nullable()->comment('職稱');
                $table->unsignedInteger('on_board_date')->nullable()->comment('到職日');
                $table->unsignedInteger('birth_date')->nullable()->comment('生日');
                $table->unsignedInteger('created_at')->nullable();
                $table->unsignedInteger('updated_at')->nullable();

                $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
}
