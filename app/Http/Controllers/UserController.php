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
        $users->volunteerTypeId = $request->volenteerTypeId;
        $result = $users->save();
        if ($result) {
            $fromDatabase = User::with('volunteer')->find($users->id);
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
//     function addUser(Request $request)
//     {
//         $user = User::create([
//             "fullName" => $request->fullname,
//             "email"=>$request->email,
//             "phone"=>$request->phone,
//             "volunteerTypeId"=>$request->volenteerTypeId,
//             "country"=>$request->country
//         ]);
//         if ($user)
//         {
//             return response()->json([
//                 'message' => 'success',
//                 'data' => $user->load('volunteer')
//             ]);
//         }
//         else {
//              return response()->json(["error" => "Failed to add User"], 500);
//             }
// }


}
