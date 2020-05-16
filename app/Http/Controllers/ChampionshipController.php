<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Championship;
use App\User;

class ChampionshipController extends Controller
{
    const VALID_EXTRA = ['participants', 'races'];

    public function index(Request $request)
    {
        $with = array_intersect(self::VALID_EXTRA, explode(',', $request->input('extra')));
        if(count($with) > 0)
            $championships = Championship::with($with)->get();
        else
            $championships = Championship::all();
        return response()->json(["code"=> 200 , "message" => "success" , "Championships" =>$championships]);
    }

    public function store(Request $request)
    {
        $values = $this->validateChampionship($request);
        
        $championship = Championship::create($values);
        $championship->save();

        return response()->json(["code"=> 200 , "message" => "created" , "championship" =>$championship]);
    }

    public function show(Request $request, $id)
    {
        $with = array_intersect(self::VALID_EXTRA, explode(',', $request->input('extra')));
        if(count($with) > 0)
            $championship = Championship::find($id)->with('participants')->get();
        else
            $championship = Championship::find($id);
        if(empty($championship))

            return response()->json(["code"=> 406 , "message" => "championship not found"], 406);
        return response()->json(["code"=> 200 , "message" => "success" , "championship" =>$championship]);
    }

    public function update(Request $request, $id)
    {
        $championship = Championship::find($id);
        if(empty($championship))
            return response()->json(["code"=> 406 , "message" => "championship not found"], 406);

        $values = $this->validateChampionship($request);
        $championship->update($values);

        return response()->json(["code"=> 200 , "message" => "updated" , "championship" =>$championship]);
    }

    public function destroy(Request $request, $id)
    {
        $championship = Championship::find($id);
        if(empty($championship))
            return response()->json(["code"=> 406 , "message" => "championship not found"], 406);

        $championship->delete();

        return response()->json(["code"=> 200 , "message" => "deleted" , "championship" =>$championship]);
    }

    public function addParticipant(Request $request, $id)
    {
        $user = User::find($request->input('user_id'));
        if(empty($user))
            return response()->json(["code"=> 406 , "message" => "user not found"], 406);

        $championship = Championship::find($id);
        if(empty($championship))
            return response()->json(["code"=> 406 , "message" => "championship not found"], 406);

        if($championship->participants->contains($user))
            return response()->json(["code"=> 406 , "message" => "user already participating in the championship"]);
        else
        {
            $championship->participants()->save($user);

            return response()->json(["code"=> 200 , "message" => "user added to championship"]);
        }
    }

    public function removeParticipant(Request $request, $id)
    {
        $user = User::find($request->input('user_id'));
        if(empty($user))
            return response()->json(["code"=> 406 , "message" => "user not found"], 406);

        $championship = Championship::find($id);
        if(empty($championship))
            return response()->json(["code"=> 406 , "message" => "championship not found"], 406);

        if($championship->participants->contains($user))
        {
            app('db')->table('championship_user')
                ->where('user_id', $user->id)
                ->where('championship_id', $championship->id)
                ->delete();

            return response()->json(["code"=> 200 , "message" => "user removed from championship"]);
        }
        else
        return response()->json(["code"=> 406 , "message" => "user doesn't belong to championship"], 406);
    }

    private function validateChampionship(Request $request)
    {
        $values = $this->validate($request, [
            'name' => 'required',
            'date' => 'required|date_format:"Y-m-d"'
        ]);
        
        return $values;
    }
}
