<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aircraft_id')->constrained('aircrafts')->onUpdate('cascade')->onDelete('cascade');
            $table->string('airline');
            $table->string('origin_airport_name');
            $table->string('origin_airport_code');
            $table->string('destination_airport_name');
            $table->string('destination_airport_code');
            $table->datetime('departure_time');
            $table->datetime('arrival_time');
            $table->float('flight_time');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('flights');
    }
}
