<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Aircraft;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class FleetController extends Controller
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
        $aircrafts = Aircraft::all();
        // foreach ($aircrafts as $aircraft) {
        //     $aircraft->roles;
        // }
        return response()->json([
            'message' => 'success',
            'aircrafts' => $aircrafts
        ], 200);
    }

    /**
     * Response one data by id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $aircraftId)
    {
        $aircraft = Aircraft::find($aircraftId);
        return response()->json([
            'message' => 'success',
            'aircraft' => $aircraft,
        ], 200);
    }

    /**
     * Create new data
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:1,100',
            'last_name' => 'required|string|between:1,100',
            'phone' => 'required',
            'birthday' => 'required',
            'company' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            // 'roles' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $aircraft = Aircraft::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        
        // $roles = Role::whereIn('name', $request->roles)->get();
        $roleIds = [2];
        // foreach ($roles as $role) {
        //     array_push($roleIds, $role->id);
        // }
        $aircraft->roles()->attach($roleIds);

        return response()->json([
            'message' => 'aircraft successfully registered',
            'aircraft' => $aircraft
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
        // Update aircraft
        $request->validate([
            'first_name' => 'required|string|between:1,100',
            'last_name' => 'required|string|between:1,100',
            'phone' => 'required',
            'birthday' => 'required',
            'company' => 'required',
            'status' => 'required',
            'email' => 'required|string|email|max:100',
            'password' => 'confirmed',
        ]);
        $aircraft = Aircraft::find($request->id);
        if ($request->password) {
            $aircraft -> update([
                'password' => bcrypt($request->password),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'birthday' => $request->birthday,
                'company' => $request->company,
                'status' => $request->status,
                'email' => $request->email,
            ]);
        } else {
            $aircraft -> update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'birthday' => $request->birthday,
                'company' => $request->company,
                'status' => $request->status,
                'email' => $request->email,
            ]);
        }

        return response()->json([
            'message' => 'aircraft successfully updated',
            'aircraft' => $aircraft
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $aircraftId)
    {
        //delete aircraft
        $aircraft = Aircraft::find($aircraftId);
        $aircraft -> delete();
        $aircrafts = Aircraft::all();
        // foreach ($aircrafts as $aircraft) {
        //     $aircraft->roles;
        // }
        return response()->json([
            'message' => 'successfully deleted',
            'aircrafts' => $aircrafts
        ], 200);
    }
}
