<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('agent_user')) {
            Schema::create('agent_user', function (Blueprint $table) {
                $table->unsignedInteger('agent_id');
                $table->unsignedInteger('user_id');
                $table->string('email_auth_token')->nullable();

                $table->foreign('agent_id')
                    ->references('id')->on('agents')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

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
        Schema::dropIfExists('agent_user');
    }
}
