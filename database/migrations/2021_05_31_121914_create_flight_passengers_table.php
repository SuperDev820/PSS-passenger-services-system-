<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightPassengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained('users')->nullable();
            $table->foreignId('aircraft_flight_id')->constrained('aircraft_flights')->nullable();
            $table->string('seat')->nullable();
            $table->string('book_reference')->unique()->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('flight_passengers');
    }
}
