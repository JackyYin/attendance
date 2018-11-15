<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('company_id');
                $table->unsignedInteger('department_id');
                $table->string('name', 50);
                $table->unsignedSmallInteger('priority');
                $table->unsignedInteger('created_at')->nullable();
                $table->unsignedInteger('updated_at')->nullable();

                $table->foreign('company_id')
                    ->references('id')->on('companies')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('department_id')
                    ->references('id')->on('departments')
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
        Schema::dropIfExists('roles');
    }
}
