<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyContactPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('company_contact_persons')) {
            Schema::create('company_contact_persons', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('company_id');
                $table->string('name', 50);
                $table->string('email');
                $table->string('phone_number', 20);
                $table->unsignedInteger('created_at');
                $table->unsignedInteger('updated_at');

                $table->foreign('company_id')
                    ->references('id')->on('companies')
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
        Schema::dropIfExists('company_contact_persons');
    }
}
