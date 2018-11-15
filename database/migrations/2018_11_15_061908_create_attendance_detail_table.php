<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('attendance_detail')) {
            Schema::create('attendance_detail', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('attendance_id');
                $table->string('latitude', 50)->nullable();
                $table->string('longitude', 50)->nullable();
                $table->string('wifi_bssid')->nullable();
                $table->unsignedTinyInteger('via')->nullable();
                $table->unsignedInteger('created_at')->nullable();
                $table->unsignedInteger('updated_at')->nullable();

                $table->foreign('attendance_id')
                    ->references('id')->on('attendances')
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
        Schema::dropIfExists('attendance_detail');
    }
}
