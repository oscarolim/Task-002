<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\User;

class UserController extends Controller
{
    const VALID_EXTRA = ['championships'];

    public function index(Request $request)
    {
        $with = array_intersect(self::VALID_EXTRA, explode(',', $request->input('extra')));
        if(count($with) > 0)
            $users = User::with('championships')->get();
        else
            $users = User::all();
        return response()->json(["code"=> 200 , "message" => "success" , "users" =>$users]);
    }

    public function store(Request $request)
    {
        $values = $this->validateUser($request);
        
        $user = User::create($values);
        $user->apikey = Str::random(64);
        $user->save();

        return response()->json(["code"=> 200 , "message" => "created" , "user" =>$user]);
    }

    public function show(Request $request, $id)
    {
        $with = array_intersect(self::VALID_EXTRA, explode(',', $request->input('extra')));
        if(count($with) > 0)
            $user = User::find($id)->with('championships')->get();
        else
            $user = User::find($id);
        if(empty($user))
            return response()->json(["code"=> 406 , "message" => "user not found"], 406);
        return response()->json(["code"=> 200 , "message" => "success" , "user" =>$user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(empty($user))
            return response()->json(["code"=> 406 , "message" => "user not found"], 406);

        if($request->user()->id != $user->id)
            return response()->json(["code"=> 406 , "message" => "not allowed to change another user's details"], 406);

        $values = $this->validateUser($request);
        $user->update($values);

        return response()->json(["code"=> 200 , "message" => "updated" , "user" =>$user]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        if(empty($user))
            return response()->json(["code"=> 406 , "message" => "user not found"], 406);

        if($request->user()->id != $user->id)
            return response()->json(["code"=> 406 , "message" => "not allowed to delete another user"], 406);

        $user->delete();

        return response()->json(["code"=> 200 , "message" => "deleted" , "user" =>$user]);
    }

    private function validateUser(Request $request)
    {
        $values = $this->validate($request, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->user())
            ],
            'number' => 'integer|gte:0'
        ]);
        
        return $values;
    }
}
