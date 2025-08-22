<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('participants');
            $table->string('information')->nullable();
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('status_id')->constrained('status');
            $table->foreignId('instansi_id')->constrained('instansi');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
