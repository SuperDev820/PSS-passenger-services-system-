<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\AircraftFlight;
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

    public function getAircraftFlights() {
        $current_date = Carbon::now()->timezone('Australia/Sydney')->format('Y-m-d');
        $current_day = Carbon::now()->timezone('Australia/Sydney')->dayOfWeek;
        // $current_day = Carbon::now()->format('l');
        $aircraft_flights = AircraftFlight::where('date', $current_date)->get();
        if (count($aircraft_flights) > 0) {
            foreach ($aircraft_flights as $aircraft_flight) {
                $aircraft_flight->aircraft;
                $aircraft_flight->flight;
            }
        } else {
            $flights = Flight::all();
            foreach ($flights as $flight) {
                if (in_array($current_day, $flight->operation_days)) {
                    $aircraft_flight = new AircraftFlight;
                    $aircraft_flight->flight_id = $flight->id;
                    $aircraft_flight->date = $current_date;
                    $aircraft_flight->departure_time = $flight->departure_time;
                    $aircraft_flight->arrival_time = $flight->arrival_time;
                    $aircraft_flight->flight_time = $flight->flight_time;
                    $aircraft_flight->save();
                }
            }
            $aircraft_flights = AircraftFlight::where('date', $current_date)->get();
            foreach ($aircraft_flights as $aircraft_flight) {
                $aircraft_flight->aircraft;
                $aircraft_flight->flight;
            }
        }
        return response()->json([
            'message' => 'success',
            'aircraft_flights' => $aircraft_flights
        ], 200);
    }

    public function saveAircraftFlight(Request $request) {
        $current_date = Carbon::now()->timezone('Australia/Sydney')->format('Y-m-d');
        $aircraft_flights = AircraftFlight::where('date', $current_date)
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
            $aircraft_flights = AircraftFlight::where('date', $current_date)->get();
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
}
