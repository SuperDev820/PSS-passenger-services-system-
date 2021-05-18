<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class PassengerController extends Controller
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
        $admin_passengers=[];
        $passengers = User::all();
        foreach ($passengers as $passenger) {
            $passenger->roles;
            foreach ($passenger->roles as $role) {
                if ($role->name == "Passenger") {
                    array_push($admin_passengers, $passenger);
                }
            }
        }
        return response()->json([
            'message' => 'success',
            'passengers' => $admin_passengers
        ], 200);
    }

    /**
     * Response one data by id
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $passengerId)
    {
        $passenger = User::find($passengerId);
        $roleNames = [];
        foreach ($passenger->roles as $role) {
            array_push($roleNames, $role->name);
        }
        $passenger->roleNames = $roleNames;
        return response()->json([
            'message' => 'success',
            'passenger' => $passenger,
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

        $passenger = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        
        // $roles = Role::whereIn('name', $request->roles)->get();
        $roleIds = [2];
        // foreach ($roles as $role) {
        //     array_push($roleIds, $role->id);
        // }
        $passenger->roles()->attach($roleIds);

        return response()->json([
            'message' => 'passenger successfully registered',
            'passenger' => $passenger
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
        // Update passenger
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
        $passenger = User::find($request->id);
        if ($request->password) {
            $passenger -> update([
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
            $passenger -> update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'birthday' => $request->birthday,
                'company' => $request->company,
                'status' => $request->status,
                'email' => $request->email,
            ]);
        }

        // $roles = Role::whereIn('name', $request->roles)->get();
        // $roleIds = [];
        // foreach ($roles as $role) {
        //     array_push($roleIds, $role->id);
        // }
        // $passenger->roles()->sync($roleIds);

        return response()->json([
            'message' => 'passenger successfully updated',
            'passenger' => $passenger
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $passengerId)
    {
        //delete passenger
        $passenger = User::find($passengerId);
        $passenger -> delete();
        $admin_passengers=[];
        $passengers = User::all();
        foreach ($passengers as $passenger) {
            $passenger->roles;
            foreach ($passenger->roles as $role) {
                if ($role->name == "Passenger") {
                    array_push($admin_passengers, $passenger);
                }
            }
        }
        return response()->json([
            'message' => 'successfully deleted',
            'passengers' => $admin_passengers
        ], 200);
    }
}
