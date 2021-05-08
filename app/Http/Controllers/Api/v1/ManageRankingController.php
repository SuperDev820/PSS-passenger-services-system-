<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Models\Sex;
use App\Models\Club;
use Illuminate\Support\Facades\DB;
use App\Models\Modality;
use App\Models\Participant;
use App\Models\Com_cat_mod_participant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ManageRankingController extends Controller
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
    public function getAllCategoryModality()
    {
        $all_category_modality = [];
        $categories = Category::all();
        $modalities = Modality::all();
        foreach ($categories as $category) {
            $category->sex;
            foreach ($modalities as $modality) {
                array_push($all_category_modality, $category->name." ".$category->sex->name." ".$modality->name);
            }
        }
        return response()->json([
            'message' => 'success',
            'all_category_modality' => $all_category_modality
        ], 200);
    }

    public function getAllRankingData(Request $request) {
        $all_ranking_data = [];

        $modality = Modality::where('name', $request->modality)->first();
        $sex = Sex::where('name', $request->sex)->first();
        $category = Category::where('name', $request->category)->where('sex_id', $sex->id)->first();
        $category->competitions;
        foreach ($category->competitions as $competition) {
            $temp = DB::table('modality_competition')->where('competition_id', $competition->id)->where('modality_id', $modality->id)->get();
            // array_push($all_ranking_data, $competition);
            if (count($temp) == 0) {
                echo("false");
            } else {
                echo("true");
            }
        }
        return response()->json([
            'message' => 'success',
            'all_ranking_data' => $temp
        ], 200);
    }

    public function getRegisteredAndNonParticipants($competitionId)
    {
        $registered_participants = [];
        $non_participants = [];

        $participants = Participant::all();
        foreach ($participants as $participant) {
            $participant->sex;
            $participant->club;

            $com_cat_mod_participant = Com_cat_mod_participant::where('participant_id', $participant->id)
                                                                    ->where('competition_id', $competitionId)
                                                                    ->get();
            if (count($com_cat_mod_participant) > 0) {
                array_push($registered_participants, $participant);
            } else {
                array_push($non_participants, $participant);
            }
        }

        return (object)[
            'registered_participants' => $registered_participants,
            'non_participants' => $non_participants,
        ];
    }

    public function getParticipantsForCompetition($competitionId)
    {
        $result = $this->getRegisteredAndNonParticipants($competitionId);
        return response()->json([
            'message' => 'success',
            'registered_participants' => $result->registered_participants,
            'non_participants' => $result->non_participants,
        ], 200);
    }

    public function addParticipantToCompetition(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:1,100',
            'surname' => 'required|string',
            'dni_ficha' => 'required|string',
            'birthday' => 'required',
            'sex' => 'required|string',
            'club' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $participant = Participant::where('dni_ficha', $request->dni_ficha)->get();

        if (count($participant) == 0) {
    
            $sex = Sex::where('name', $request->sex)->first();
            $club = Club::where('name', $request->club)->first();
            $participant = new Participant;
            $participant->name = $request->name;
            $participant->surname = $request->surname;
            $participant->dni_ficha = $request->dni_ficha;
            $participant->setDateAttribute($request->birthday);
            $participant->sex()->associate($sex);
            $participant->club()->associate($club);
            $participant->save();
            // var_dump($participant);
            $categories = $this->getCategoryFromParticipant($participant);

            if (count($categories) == 0) {
                // $participant->delete();
                return response()->json([
                    'message' => 'Any category doesnt include the participant',
                    'participant' => $participant
                ], 400);
            }

            foreach ($categories as $category) {
                foreach ($request->modality as $modality_name) {
                    $modality = Modality::where('name', $modality_name)->first();

                    $com_cat_mod_participant = new Com_cat_mod_participant;
                    $com_cat_mod_participant->competition_id = $request->competitionId;
                    $com_cat_mod_participant->participant_id = $participant->id;
                    $com_cat_mod_participant->modality_id = $modality->id;
                    $com_cat_mod_participant->category_id = $category->id;
                    $com_cat_mod_participant->save();
                }
            }

            $result = $this->getRegisteredAndNonParticipants($request->competitionId);
            return response()->json([
                'message' => 'Successfully added a participant to a competition',
                'registered_participants' => $result->registered_participants,
                'non_participants' => $result->non_participants,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Already exists such a participant',
                'participant' => $participant
            ], 201);
        }
    }

    public function registParticipantToCompetition(Request $request) {

        $validator = Validator::make($request->all(), [
            'modality' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $participant = Participant::find($request->participantId);

        $categories = $this->getCategoryFromParticipant($participant);

        if (count($categories) == 0) {
            return response()->json([
                'message' => 'Any category doesnt include the participant',
                'participant' => $participant
            ], 400);
        }

        foreach ($categories as $category) {
            foreach ($request->modality as $modality_name) {
                $modality = Modality::where('name', $modality_name)->first();

                $com_cat_mod_participant = new Com_cat_mod_participant;
                $com_cat_mod_participant->competition_id = $request->competitionId;
                $com_cat_mod_participant->participant_id = $request->participantId;
                $com_cat_mod_participant->modality_id = $modality->id;
                $com_cat_mod_participant->category_id = $category->id;
                $com_cat_mod_participant->save();
            }
        }

        $result = $this->getRegisteredAndNonParticipants($request->competitionId);
        return response()->json([
            'message' => 'Successfully registered a participant to a competition',
            'registered_participants' => $result->registered_participants,
            'non_participants' => $result->non_participants,
        ], 200);
    }

    public function getModalityOfParticipant(Request $request) {
        $modality_id_array = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                    ->where('participant_id', $request->participantId)
                                    ->pluck('modality_id');
        $modality_id = $modality_id_array->unique();
        $modality_id->all();

        $modality_participant = Modality::whereIn('id', $modality_id)->pluck('name');

        return response()->json([
            'message' => 'Success',
            'modality_participant' => $modality_participant,
        ], 200);
    }

    public function updateParticipantToCompetition(Request $request) {
        $deleteRows = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                    ->where('participant_id', $request->participantId)
                                    ->delete();
        
        $participant = Participant::find($request->participantId);
        $categories = $this->getCategoryFromParticipant($participant);

        foreach ($categories as $category) {
            foreach ($request->modality as $modality_name) {
                $modality = Modality::where('name', $modality_name)->first();

                $com_cat_mod_participant = new Com_cat_mod_participant;
                $com_cat_mod_participant->competition_id = $request->competitionId;
                $com_cat_mod_participant->participant_id = $request->participantId;
                $com_cat_mod_participant->modality_id = $modality->id;
                $com_cat_mod_participant->category_id = $category->id;
                $com_cat_mod_participant->save();
            }
        }

        $result = $this->getRegisteredAndNonParticipants($request->competitionId);
        return response()->json([
            'message' => 'Successfully updated a participant to a competition',
            'registered_participants' => $result->registered_participants,
            'non_participants' => $result->non_participants,
        ], 200);
    }

    public function unregistParticipantToCompetition(Request $request) {
        $deleteRows = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                    ->where('participant_id', $request->participantId)
                                    ->delete();

        $result = $this->getRegisteredAndNonParticipants($request->competitionId);
        return response()->json([
            'message' => 'Successfully unregistered a participant to a competition',
            'registered_participants' => $result->registered_participants,
            'non_participants' => $result->non_participants,
        ], 200);
    }

    public function getCategoryFromParticipant($participant) {
        $year = date('Y',strtotime($participant->birthday));

        $categories = Category::where('year1', '<=', $year)
                        ->where('year2', '>=', $year)
                        ->where('sex_id', $participant->sex_id)
                        ->get();
        return $categories;
    }
}
