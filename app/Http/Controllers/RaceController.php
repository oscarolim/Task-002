<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Race;
use App\User;

class RaceController extends Controller
{
    public function index(Request $request)
    {
        $races = Race::with('participants')->with('championship')->get();
        return response()->json(["code"=> 200 , "message" => "success" , "races" =>$races]);
    }

    public function store(Request $request)
    {
        $values = $this->validateRace($request);
        
        $race = Race::create($values);
        $race->save();

        return response()->json(["code"=> 200 , "message" => "created" , "race" =>$race]);
    }

    public function show(Request $request, $id)
    {
        $race = Race::find($id)->with('participants')->with('championship')->get();
        if(empty($race))

            return response()->json(["code"=> 406 , "message" => "race not found"], 406);
        return response()->json(["code"=> 200 , "message" => "success" , "race" =>$race]);
    }

    public function update(Request $request, $id)
    {
        $race = Race::find($id);
        if(empty($race))
            return response()->json(["code"=> 406 , "message" => "race not found"], 406);

        $values = $this->validateRace($request);
        $race->update($values);

        return response()->json(["code"=> 200 , "message" => "updated" , "race" =>$race]);
    }

    public function destroy(Request $request, $id)
    {
        $race = Race::find($id);
        if(empty($race))
            return response()->json(["code"=> 406 , "message" => "race not found"], 406);

        $race->delete();

        return response()->json(["code"=> 200 , "message" => "deleted" , "race" =>$race]);
    }

    public function updateResult(Request $request, $id)
    {
        $this->validate($request, [
            'points' => 'required|numeric',
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->input('user_id'));
        if(empty($user))
            return response()->json(["code"=> 406 , "message" => "user not found"], 406);

        $race = Race::find($id);
        if(empty($race))
            return response()->json(["code"=> 406 , "message" => "race not found"], 406);

        if($race->championship->participants->contains($user))
        {
            $uniqueFields = [
                'race_id' => $race->id,
                'user_id' => $user->id
            ];
            app('db')->table('race_user')->updateOrInsert($uniqueFields, ['points' => $request->input('points')]);

            return response()->json(["code"=> 200 , "message" => "user points added to race"]);
        }
        else
            return response()->json(["code"=> 406 , "message" => "user is not part of this race"]);
    }

    private function validateRace(Request $request)
    {
        $values = $this->validate($request, [
            'name' => 'required',
            'championship_id' => 'required|exists:championships,id'
        ]);
        
        return $values;
    }
}
