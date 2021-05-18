<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;

class FlightController extends Controller
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
    public function getAll()
    {
        $flights = Flight::all();
        foreach ($flights as $flight) {
            $flight->aircraft;
        }
        return response()->json([
            'message' => 'success',
            'flights' => $flights
        ], 200);
    }

    /**
     * Response one data by id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $flightId)
    {
        $flight = Flight::find($flightId);
        // $temp = Carbon::parse($flight->departure_time)->format('Y-m-d H:m');
        // $flight->departure_time = $temp;
        $flight->aircraft;
        return response()->json([
            'message' => 'success',
            'flight' => $flight,
        ], 200);
    }

    /**
     * Create new data
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'airline' => 'required',
            'aircraft' => 'required',
            'origin_airport_name' => 'required',
            'origin_airport_code' => 'required',
            'destination_airport_name' => 'required',
            'destination_airport_code' => 'required',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'flight_time' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $aircraft = Aircraft::where('registration', $request->aircraft)->get();

        $flight = new Flight;
        $flight->airline = $request->airline;
        $flight->aircraft_id = $aircraft[0]->id;
        $flight->origin_airport_name = $request->origin_airport_name;
        $flight->origin_airport_code = $request->origin_airport_code;
        $flight->destination_airport_name = $request->destination_airport_name;
        $flight->destination_airport_code = $request->destination_airport_code;
        $flight->departure_time = $request->departure_time;
        $flight->arrival_time = $request->arrival_time;
        $flight->flight_time = $request->flight_time;
        $flight->save();

        return response()->json([
            'message' => 'flight successfully registered',
            'flight' => $flight
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Update flight
        $validator = Validator::make($request->all(), [
            'airline' => 'required',
            'aircraft' => 'required',
            'origin_airport_name' => 'required',
            'origin_airport_code' => 'required',
            'destination_airport_name' => 'required',
            'destination_airport_code' => 'required',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'flight_time' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $aircraft = Aircraft::where('registration', $request->aircraft)->get();

        $flight = Flight::find($request->id);
        $flight -> update([
            'airline' => $request->airline,
            'aircraft_id' => $aircraft[0]->id,
            'origin_airport_name' => $request->origin_airport_name,
            'origin_airport_code' => $request->origin_airport_code,
            'destination_airport_name' => $request->destination_airport_name,
            'destination_airport_code' => $request->destination_airport_code,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'flight_time' => $request->flight_time,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'flight successfully updated',
            'flight' => $flight
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $flightId)
    {
        //delete flight
        $flight = Flight::find($flightId);
        $flight -> delete();
        $flights = Flight::all();
        foreach ($flights as $flight) {
            $flight->aircraft;
        }
        return response()->json([
            'message' => 'successfully deleted',
            'flights' => $flights
        ], 200);
    }
}
