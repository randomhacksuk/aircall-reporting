<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aircall_call_id')->unique();
            $table->integer('user_id')->nullable();
            $table->string('status')->nullable();
            $table->string('direction')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('answered_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->bigInteger('duration')->default(0);
            $table->string('raw_digits')->nullable();
            $table->string('voicemail')->nullable();
            $table->string('recording')->nullable();
            $table->boolean('archived');
            $table->string('number')->nullable();
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
        Schema::dropIfExists('calls');
    }
}
