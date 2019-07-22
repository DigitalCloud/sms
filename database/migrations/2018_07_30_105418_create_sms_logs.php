<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('notifiable');
            $table->string('mobile');
            $table->string('provider');
            $table->string('message')->nullable();
            $table->string('response')->nullable();
            $table->boolean('success')->nullable();
            $table->string('response_code')->nullable();
            $table->string('notification')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_sms');
    }
}
