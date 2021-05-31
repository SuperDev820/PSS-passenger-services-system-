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
            $table->string('airline_code');
            $table->string('flight_number');
            $table->string('origin_airport_name');
            $table->string('origin_airport_code')->nullable();
            $table->string('destination_airport_name');
            $table->string('destination_airport_code')->nullable();
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->string('flight_time');
            $table->json('operation_days')->nullable();
            $table->string('type')->default('REGULAR');
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
