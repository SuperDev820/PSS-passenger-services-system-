<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\AircraftFlight;
use App\Models\FlightPassenger;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DateTime;
use Carbon\Carbon;

class AircraftFlightController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth:api', ['except' => []]);
    }
    /**
     * Response all data
     *
     * @return \Illuminate\Http\Response
     */

    public function getAircraftFlightsByDate(Request $request) {
        $current_date = Carbon::now()->timezone('Australia/Sydney')->format('Y-m-d');
        // $current_day = Carbon::now()->timezone('Australia/Sydney')->dayOfWeek;
        // $current_day = Carbon::now()->format('l');
        // if ($current_day == 0) {
        //     $current_day = 7;
        // }
        $date = Carbon::create($request->date)->format('Y-m-d');
        $aircraft_flights = AircraftFlight::where('date', $date)->get();
        if (count($aircraft_flights) > 0) {
            foreach ($aircraft_flights as $aircraft_flight) {
                $aircraft_flight->aircraft;
                $aircraft_flight->flight;
            }
        }
        return response()->json([
            'message' => 'success',
            'aircraft_flights' => $aircraft_flights,
        ], 200);
    }

    public function saveAircraftFlight(Request $request) {
        $current_date = Carbon::now()->timezone('Australia/Sydney')->format('Y-m-d');
        $date = Carbon::create($request->date)->format('Y-m-d');
        $aircraft_flights = AircraftFlight::where('date', $date)
                                        ->where('flight_id', $request->flight)->get();
        if (count($aircraft_flights) > 0) {
            $aircraft_flight = $aircraft_flights[0];
            $departure_time = new DateTime($request->departure_time);
            $flight_time_object = $departure_time->diff(new DateTime($request->arrival_time));
            if ($flight_time_object->h > 0) {
                if ($flight_time_object->i > 0) {
                    $flight_time = $flight_time_object->h." hours ".$flight_time_object->i." minutes";
                } else {
                    $flight_time = $flight_time_object->h." hours";
                }
            } else {
                $flight_time = $flight_time_object->i." minutes";
            }
            $aircraft_flight -> update([
                'aircraft_id' => $request->aircraft,
                'departure_time' => $request->departure_time,
                'arrival_time' => $request->arrival_time,
                'flight_time' => $flight_time,
                'status' => 'CONFIRMED',
                'phase' => 'OPEN',
            ]);
            $aircraft_flights = AircraftFlight::where('date', $date)->get();
            foreach ($aircraft_flights as $aircraft_flight) {
                $aircraft_flight->aircraft;
                $aircraft_flight->flight;
            }
            return response()->json([
                'message' => 'success',
                'aircraft_flights' => $aircraft_flights
            ], 200);
        }
    }
    public function getFlightPassengers(Request $request, $flightId)
    {
        $aircraft_flight = AircraftFlight::find($flightId);
        $aircraft_flight->flight;
        $aircraft_flight->aircraft;
        
        $flight_passengers = FlightPassenger::where('aircraft_flight_id', $flightId)->get();
        foreach ($flight_passengers as $flight_passenger) {
            $flight_passenger->passenger;
            $flight_passenger->aircraftFlight->flight;
            $flight_passenger->aircraftFlight->aircraft;
        }
        return response()->json([
            'message' => 'success',
            'aircraft_flight' => $aircraft_flight,
            'flight_passengers' => $flight_passengers,
        ], 200);
    }
}
