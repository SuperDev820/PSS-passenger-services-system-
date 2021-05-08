<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Models\Sex;
use App\Models\Club;
use Illuminate\Support\Facades\DB;
use App\Models\Modality;
use App\Models\Participant;
use App\Models\Heat_configuration;
use App\Models\Com_cat_mod_participant;
use App\Models\Heat_score;
use App\Models\Round_heat;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class LiveManagementController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['initCompetitionHeats']]);
    }
    /**
     * Response all data
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoryModalityWithPart($competitionId)
    {
        $category_modality_with_part = [];
        $categories = Category::all();
        $modalities = Modality::all();
        foreach ($categories as $category) {
            $category->sex;
            foreach ($modalities as $modality) {
                $temps = Com_cat_mod_participant::where('competition_id', $competitionId)
                                        ->where('category_id', $category->id)
                                        ->where('modality_id', $modality->id)->get();
                if (count($temps) > 0) {
                    array_push($category_modality_with_part, $category->name." ".$category->sex->name." ".$modality->name);
                }
            }
        }
        return response()->json([
            'message' => 'success',
            'category_modality_with_part' => $category_modality_with_part
        ], 200);
    }

    public function getParticipantsByCompetitionCategoryModality(Request $request)
    {
        $participants = [];
        $str = explode(" ", $request->categoryModality);
        $sex = Sex::where('name', $str[1])->first();
        $category = Category::where('name', $str[0])->where('sex_id', $sex->id)->first();
        $modality = Modality::where('name', $str[2])->first();
        $temps = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                        ->where('category_id', $category->id)
                                        ->where('modality_id', $modality->id)->get();
        foreach ($temps as $temp) {
            $participant = Participant::find($temp->participant_id);
            $participant->club;
            $participant->sex;
            array_push($participants, $participant);
        }

        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                            ->where('category_id', $category->id)
                                                            ->where('modality_id', $modality->id)->get();
        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->where('round', 1)->get();
        $status = false;
        if (count($round_heats) > 0) {
            $status = true;
        }
        
        return response()->json([
            'message' => 'success',
            'participants_competition_category_modality' => $participants,
            'category_id' => $category->id,
            'modality_id' => $modality->id,
            'status' => $status,
        ], 200);
    }

    public function unregistParticipantToCompetitionCategoryModality(Request $request)
    {
        $str = explode(" ", $request->categoryModality);
        $sex = Sex::where('name', $str[1])->first();
        $category = Category::where('name', $str[0])->where('sex_id', $sex->id)->first();
        $modality = Modality::where('name', $str[2].' '.$str[3])->first();

        $deleteRows = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                    ->where('participant_id', $request->participantId)
                                    ->where('category_id', $category->id)
                                    ->where('modality_id', $modality->id)
                                    ->delete();
        $participants = [];
        $temps = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                        ->where('category_id', $category->id)
                                        ->where('modality_id', $modality->id)->get();
        
        foreach ($temps as $temp) {
            $participant = Participant::find($temp->participant_id);
            $participant->club;
            $participant->sex;
            array_push($participants, $participant);
        }
        return response()->json([
            'message' => 'Successfully unregistered a participant to a competition',
            'participants_competition_category_modality' => $participants
        ], 200);
    }

    public function createFirstCompetitionBoxes(Request $request)
    {
        $com_cat_mod_participants = Com_cat_mod_participant::where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();
        $round_heats = Round_heat::where('com_cat_mod_participant_id', $com_cat_mod_participants[0]->id)->where('round', 1)->get();
        if (count($round_heats) == 0) {
            $heat_configuration = Heat_configuration::where('participant_number', count($com_cat_mod_participants))->first();
            $s = 0;
            foreach ($heat_configuration->assign_array as $index => $heat_items) {
                for ($i = 1; $i <= $heat_items; $i++) {
                    $round_heat = new Round_heat;
                    $round_heat->round = 1;
                    $round_heat->heat = $index + 1;
                    $round_heat->com_cat_mod_participant_id = $com_cat_mod_participants[$s]->id;
                    $round_heat->lycra_id = $i;
                    $round_heat->save();
                    $s++;
                }
            }
            return response()->json([
                'message' => 'success',
            ], 200);
        }
    }

    public function initCompetitionHeats(Request $request)
    {
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();

        $all_round_heats = [];
        for ($i=1; ;$i++) {
            $array_rounds = [];
            $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->where('round', $i)->get();
            if (count($round_heats) > 0) {
                for ($j=1; ;$j++) {
                    $array_heats = [];
                    $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)->where('round', $i)->where('heat', $j)->get();
                    if (count($round_heats) > 0) {
                        foreach ($round_heats as $round_heat) {
                            $round_heat->com_cat_mod_participant->participant;
                            $round_heat->com_cat_mod_participant->competition;
                            $round_heat->com_cat_mod_participant->category->sex;
                            $round_heat->com_cat_mod_participant->modality;
                            $round_heat->lycra;
                            array_push($array_heats, $round_heat);
                        }
                    } else {
                        break;
                    }
                    array_push($array_rounds, $array_heats);
                }
            } else {
                break;
            }
            array_push($all_round_heats, $array_rounds);
        }

        return response()->json([
            'message' => 'success',
            'all_round_heats' => $all_round_heats,
        ], 200);
    }

    public function setProgressStatus(Request $request)
    {
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();
        $active_round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                                    ->where('status', 3)->get();
        $affected = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                            ->where('round', $request->round)
                            ->where('heat', $request->heat)->get();

        if (count($active_round_heats) != 0) {
            if ($affected[0]->status == 3) {
                return response()->json([
                    'message' => 'success',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'failed',
                ], 200);
            }
        } else {
            foreach ($affected as $temp) {
                $temp->update(['status' => 3]);
            }
            return response()->json([
                'message' => 'success',
            ], 200);
        }
    }

    public function initHeatDetails(Request $request)
    {
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $request->competitionId)
                                                            ->where('category_id', $request->categoryId)
                                                            ->where('modality_id', $request->modalityId)->get();
        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                                ->where('round', $request->round)
                                ->where('heat', $request->heat)->get();
        $heat_scores = [];
        $judge_role = Role::where('name', 'Judge')->first();
        foreach ($round_heats as $round_heat) {
            $round_heat->com_cat_mod_participant->participant;
            $round_heat->com_cat_mod_participant->competition;
            $round_heat->com_cat_mod_participant->category->sex;
            $round_heat->com_cat_mod_participant->modality;
            $round_heat->lycra;

            $temps = Heat_score::where('round_heat_id', $round_heat->id)->get();
            if (count($temps) == 0) {
                foreach ($judge_role->users as $judge) {
                    $heat_score = new Heat_score;
                    $heat_score->round_heat_id = $round_heat->id;
                    $heat_score->judge_id = $judge->id;
                    $heat_score->save();
                }
            }
            $average =  [
                'round_heat_id' => 0,
                'judge_id' => 'Average',
                'wave_1' => 0,
                'wave_2' => 0,
                'wave_3' => 0,
                'wave_4' => 0,
                'wave_5' => 0,
                'wave_6' => 0,
                'wave_7' => 0,
                'wave_8' => 0,
                'wave_9' => 0,
                'wave_10' => 0,
            ];
            $heat_scores_temp = [];
            $temps = Heat_score::where('round_heat_id', $round_heat->id)->orderBy('judge_id')->get();
            foreach ($temps as $temp) {
                $temp->round_heat->com_cat_mod_participant->participant;
                $temp->round_heat->com_cat_mod_participant->competition;
                $temp->round_heat->com_cat_mod_participant->category->sex;
                $temp->round_heat->com_cat_mod_participant->modality;
                $temp->round_heat->lycra;

                $average['round_heat_id'] = $temp->round_heat_id;
                $average['wave_1'] = $average['wave_1'] + $temp->wave_1/3;
                $average['wave_2'] = $average['wave_2'] + $temp->wave_2/3;
                $average['wave_3'] = $average['wave_3'] + $temp->wave_3/3;
                $average['wave_4'] = $average['wave_4'] + $temp->wave_4/3;
                $average['wave_5'] = $average['wave_5'] + $temp->wave_5/3;
                $average['wave_6'] = $average['wave_6'] + $temp->wave_6/3;
                $average['wave_7'] = $average['wave_7'] + $temp->wave_7/3;
                $average['wave_8'] = $average['wave_8'] + $temp->wave_8/3;
                $average['wave_9'] = $average['wave_9'] + $temp->wave_9/3;
                $average['wave_10'] = $average['wave_10'] + $temp->wave_10/3;
                array_push($heat_scores_temp, $temp);
            }
            array_push($heat_scores_temp, $average);
            array_push($heat_scores, $heat_scores_temp);
        }
        
        return response()->json([
            'message' => 'success',
            'round_heats' => $round_heats,
            'heat_scores' => $heat_scores,
        ], 200);
    }

    public function sortAverage($average) 
    {
        $scores = [];
        for ($i=1; $i<=10; $i++) {
            array_push($scores, $average['wave_'.$i]);
        }
        rsort($scores);
        return [
            'first_score' => $scores[0],
            'second_score' => $scores[1],
        ];
    }

    public function storeFinalHeatResults(Request $request)
    {
        $points = [];
        foreach ($request->heat_scores as $heat_scores) {
            $round_heat = Round_heat::find($heat_scores[0]['round_heat_id']);
            $round_heat->update([
                'status' => 1,
            ]);
            foreach ($heat_scores as $heat_score) {
                if ($heat_score['judge_id'] != "Average") {
                    $temp = Heat_score::find($heat_score['id']);
                    $temp->update([
                        'wave_1' => $heat_score['wave_1'],
                        'wave_2' => $heat_score['wave_2'],
                        'wave_3' => $heat_score['wave_3'],
                        'wave_4' => $heat_score['wave_4'],
                        'wave_5' => $heat_score['wave_5'],
                        'wave_6' => $heat_score['wave_6'],
                        'wave_7' => $heat_score['wave_7'],
                        'wave_8' => $heat_score['wave_8'],
                        'wave_9' => $heat_score['wave_9'],
                        'wave_10' => $heat_score['wave_10'],
                    ]);
                }
            }

            $average =  [
                'wave_1' => 0,
                'wave_2' => 0,
                'wave_3' => 0,
                'wave_4' => 0,
                'wave_5' => 0,
                'wave_6' => 0,
                'wave_7' => 0,
                'wave_8' => 0,
                'wave_9' => 0,
                'wave_10' => 0,
            ];
            $temps = Heat_score::where('round_heat_id', $round_heat->id)->orderBy('judge_id')->get();
            foreach ($temps as $temp) {
                $average['wave_1'] = $average['wave_1'] + $temp->wave_1/3;
                $average['wave_2'] = $average['wave_2'] + $temp->wave_2/3;
                $average['wave_3'] = $average['wave_3'] + $temp->wave_3/3;
                $average['wave_4'] = $average['wave_4'] + $temp->wave_4/3;
                $average['wave_5'] = $average['wave_5'] + $temp->wave_5/3;
                $average['wave_6'] = $average['wave_6'] + $temp->wave_6/3;
                $average['wave_7'] = $average['wave_7'] + $temp->wave_7/3;
                $average['wave_8'] = $average['wave_8'] + $temp->wave_8/3;
                $average['wave_9'] = $average['wave_9'] + $temp->wave_9/3;
                $average['wave_10'] = $average['wave_10'] + $temp->wave_10/3;
            }
            $ret = $this->sortAverage($average);
            $first_score = floatval(number_format($ret['first_score'], 2, '.', ''));
            $second_score = floatval(number_format($ret['second_score'], 2, '.', ''));
            $round_heat->update([
                'first_score' => $first_score,
                'second_score' => $second_score,
                'points' => $first_score + $second_score,
            ]);
            // array_push($points, $round_heat->points);
            $points["$round_heat->id"] = $round_heat->points;
        }
        arsort($points);
        $index = 1;
        foreach ($points as $key => $point) {
            $round_heat = Round_heat::find($key);
            $round_heat->update([
                'position' => $index,
            ]);
            $index++;
        }

        $round_heat = Round_heat::find($request->heat_scores[0][0]['round_heat_id']);
        $current_round = $round_heat->round;
        $round_heat->com_cat_mod_participant;
        $current_competition = $round_heat->com_cat_mod_participant->competition_id;
        $current_category = $round_heat->com_cat_mod_participant->category_id;
        $current_modality = $round_heat->com_cat_mod_participant->modality_id;
        $com_cat_mod_participant_ids = Com_cat_mod_participant::select('id')->where('competition_id', $current_competition)
                                                            ->where('category_id', $current_category)
                                                            ->where('modality_id', $current_modality)->get();
        $round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                                ->where('round', $current_round)->get();
        $next_round_heats = Round_heat::whereIn('com_cat_mod_participant_id', $com_cat_mod_participant_ids)
                                    ->where('round', $current_round+1)->get();
        $isCreatingNew = true;
        $new_round_heats = [];
        foreach ($round_heats as $round_heat) {
            if (($round_heat->status != 1) || (count($round_heats) < 6)) {
                $isCreatingNew = false;
            }
            if (($round_heat->position == 1) || ($round_heat->position == 2)) {
                array_push($new_round_heats, $round_heat);
            }
        }
        if ($isCreatingNew && (count($new_round_heats) > 0) && (count($next_round_heats) == 0)) {
            $heat_configuration = Heat_configuration::where('participant_number', count($new_round_heats))->first();
            $heat_number = count($heat_configuration->assign_array);
            switch (count($new_round_heats)) {
                case 6:
                    foreach ($heat_configuration->assign_array as $index => $heat_items) {
                        for ($i = 1; $i <= $heat_items; $i++) {
                            $round_heat = new Round_heat;
                            $round_heat->round = $current_round + 1;
                            $round_heat->heat = $index + 1;
                            if ($index == 0) {
                                if ($this->getFirstSecondParticipant($new_round_heats, $heat_number*($i-1)+1, 1) != null) {
                                    $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $heat_number*($i-1)+1, 1)
                                                                                ->com_cat_mod_participant_id;
                                } else {
                                    $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, 2, 2)
                                                                                ->com_cat_mod_participant_id;
                                }
                            } else {
                                if ($this->getFirstSecondParticipant($new_round_heats, $heat_number*($i-1)+1, 2) != null) {
                                    $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $heat_number*($i-1)+1, 2)
                                                                                ->com_cat_mod_participant_id;
                                } else {
                                    $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, 2, 1)
                                                                                ->com_cat_mod_participant_id;
                                }
                            }
                            
                            $round_heat->lycra_id = $i;
                            $round_heat->save();
                        }
                    }
                    break;
                case 12:
                    $prev_heat = 1;
                    $lycra_id = 1;
                    for ($i = 1; $i <= count($new_round_heats); $i++) {
                        $position = ($i % 2 == 0) ? 2 : 1;
                        $round_heat = new Round_heat;
                        $round_heat->round = $current_round + 1;
                        $round_heat->heat = ($i % $heat_number == 0) ? $heat_number : $i % $heat_number;
                        $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $prev_heat, $position)
                                                                    ->com_cat_mod_participant_id;
                        $round_heat->lycra_id = $lycra_id;
                        $round_heat->save();
                        if ($i % 2 == 0) {
                            $prev_heat++;
                        }
                        if ($i % $heat_number == 0) {
                            $lycra_id++;
                        }
                    }
                    break;
                case 20:
                    $lycra_id = 1;
                    $prev_heat_number = count($new_round_heats)/2;
                    $position = 1;
                    $prev_heat = 1;
                    for ($i = 1; $i <= count($new_round_heats); $i++) {
                        $round_heat = new Round_heat;
                        $round_heat->round = $current_round + 1;
                        $round_heat->heat = ($i % $heat_number == 0) ? $heat_number : $i % $heat_number;
                        $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $prev_heat, $position)
                                                                    ->com_cat_mod_participant_id;
                        $round_heat->lycra_id = $lycra_id;
                        $round_heat->save();
                        $prev_heat++;
                        if ($i == $prev_heat_number) {
                            $prev_heat = 1;
                            $position++;
                        }
                        if ($i % $heat_number == 0) {
                            $lycra_id++;
                        }
                    }
                    break;
                default:
                    $lycra_id = 1;
                    for ($i = 1; $i <= count($new_round_heats)/2; $i++) {
                        $round_heat = new Round_heat;
                        $round_heat->round = $current_round + 1;
                        $round_heat->heat = ($i % $heat_number == 0) ? $heat_number : $i % $heat_number;
                        $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $i, 1)
                                                                    ->com_cat_mod_participant_id;
                        $round_heat->lycra_id = $lycra_id;
                        $round_heat->save();
                        if ($i % $heat_number == 0) {
                            $lycra_id++;
                        }
                    }
                    $lycra_id = 2;
                    for ($i = count($new_round_heats)/2; $i >= 1; $i--) {
                        if ($i % $heat_number == 0) {
                            $lycra_id++;
                        }
                        $round_heat = new Round_heat;
                        $round_heat->round = $current_round + 1;
                        $round_heat->heat = ($i % $heat_number == 0) ? $heat_number : $i % $heat_number;
                        $round_heat->com_cat_mod_participant_id = $this->getFirstSecondParticipant($new_round_heats, $i, 2)
                                                                    ->com_cat_mod_participant_id;
                        $round_heat->lycra_id = $lycra_id;
                        $round_heat->save();
                    }
            }
        }

        return response()->json([
            'message' => 'success',
            'data' => $points,
        ], 200);
    }

    public function getFirstSecondParticipant($round_heats, $prev_heat, $position)
    {
        foreach ($round_heats as $round_heat) {
            if (($round_heat->heat == $prev_heat) && ($round_heat->position == $position)) {
                return $round_heat;
            }
        }
        return null;
    }
}
