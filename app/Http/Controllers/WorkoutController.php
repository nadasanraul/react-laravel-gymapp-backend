<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Set;

class WorkoutController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(3);
        $sets = Set::with(['exercise', 'user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $workouts = [];

        foreach($sets as $set) {
            $date = date('Y-m-d', strtotime($set->for_day));
            if(!array_key_exists($date, $workouts)){
                $workouts[$date] = (object)[
                    'exercises' => [
                    ]
                ];
            }
        }

        foreach($workouts as $key => $workout) {
            foreach($sets as $set) {
                $date = date('Y-m-d', strtotime($set->for_day));
                if(!array_key_exists($set->exercise->name, $workout->exercises) && $date === $key){
                    $workout->exercises[$set->exercise->name] = (object)[
                        'category' => $set->exercise->category->name,
                        'sets' => [
                            [
                                'id' => $set->id,
                                'weight' => $set->weight,
                                'reps' => $set->reps
                            ]
                        ],
                        'total_weight' => $set->total_weight
                    ];
                } else if($date === $key) {
                    $arr = [
                        'id' => $set->id,
                        'weight' => $set->weight,
                        'reps' => $set->reps
                    ];
                    $workout->exercises[$set->exercise->name]->total_weight += $set->total_weight;
                    array_push($workout->exercises[$set->exercise->name]->sets, $arr);
                }
            }
        }

        return $workouts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($day)
    {
        $user = User::find(3);
        $sets = Set::with(['exercise', 'user'])
            ->where('user_id', $user->id)
            ->where('for_day', $day)
            ->orderBy('created_at', 'asc')
            ->get();

        $exercises  = [];
        
        foreach($sets as $set) {
            if(!array_key_exists($set->exercise->name, $exercises)){
                $exercises[$set->exercise->name] = (object)[
                    'category' => $set->exercise->category->name,
                    'sets' => [
                        [
                            'id' => $set->id,
                            'weight' => $set->weight,
                            'reps' => $set->reps
                        ]
                    ],
                    'total_weight' => $set->total_weight
                ];
                
            } else {
                $arr = [
                    'id' => $set->id,
                    'weight' => $set->weight,
                    'reps' => $set->reps
                ];
                $exercises[$set->exercise->name]->total_weight += $set->total_weight;
                array_push($exercises[$set->exercise->name]->sets, $arr);
            }
        }

        return [
            'day' => $day,
            'exercises' => $exercises
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
