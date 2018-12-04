<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('networks')) {
            Schema::create('networks', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('company_id');
                $table->unsignedInteger('department_id');
                $table->string('wifi_ssid')->comment('wifi 名稱');
                $table->string('wifi_bssid')->comment('wifi bssid');
                $table->unsignedInteger('created_at');
                $table->unsignedInteger('updated_at');

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
        Schema::dropIfExists('networks');
    }
}
