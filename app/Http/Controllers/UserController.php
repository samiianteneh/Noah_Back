<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
  function usersList($id = null)
    {
        if ($id)
        {
            return User::with('volunteer')->find($id);
        }
        else
        {
            return User::with('volunteer')->get();
        }
    }

    function addUser(Request $request)
    {
        $users = new User;
        $users->fullname = $request->fullname;
        $users->email  = $request->email ;
        $users->phone  = $request->phone ;
        $users->country = $request->country;
        $users->volenteerTypeId = $request->volenteerTypeId;
        $users->image = $request->image;
        $result = $users->save();
        if ($result) {
            $fromDatabase = User::find($users->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];

            return response()->json($response);
        }
        else {
             return response()->json(["error" => "Failed to add User"], 500);
            }


}


}
