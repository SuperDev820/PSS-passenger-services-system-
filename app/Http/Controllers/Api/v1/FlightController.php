<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

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
        // foreach ($flights as $flight) {
        //     $flight->roles;
        // }
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
            'registration' => 'required|string|between:1,100',
            'model' => 'required|string|between:1,100',
            'seat_config' => 'required|numeric',
            'total_seat' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $flight = Flight::create(array_merge(
                    $validator->validated(),
                ));

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
        $request->validate([
            'registration' => 'required|string|between:1,100',
            'model' => 'required|string|between:1,100',
            'seat_config' => 'required|numeric',
            'total_seat' => 'required|numeric',
        ]);
        $flight = Flight::find($request->id);
        $flight -> update([
            'registration' => $request->registration,
            'model' => $request->model,
            'seat_config' => $request->seat_config,
            'total_seat' => $request->total_seat,
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
        // foreach ($flights as $flight) {
        //     $flight->roles;
        // }
        return response()->json([
            'message' => 'successfully deleted',
            'flights' => $flights
        ], 200);
    }
}
