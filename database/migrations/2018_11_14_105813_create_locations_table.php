<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('company_id');
                $table->unsignedInteger('department_id');
                $table->string('name', 50)->nullable();
                $table->string('latitude', 50)->nullable();
                $table->string('longitude', 50)->nullable();
                $table->string('address')->nullable();
                $table->unsignedInteger('legal_distance')->default(100)->comment('允許打卡距離(公尺)');
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
        Schema::dropIfExists('locations');
    }
}
