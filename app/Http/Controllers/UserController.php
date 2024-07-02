<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function usersList($id = null)
{
    $query = User::where('isAdmin', false)
                 ->with('volunteer'); // Ensure isAdmin is 0 and eager load the volunteer relationship

    if ($id) {
        return $query->select('id', 'fullName', 'phone','email', 'country', 'volunteerTypeId', 'created_at', 'updated_at')->find($id);
    } else {
        return $query->select('id', 'fullName', 'phone', 'email','country', 'volunteerTypeId', 'created_at', 'updated_at')->get();
    }
}



    function addUser(Request $request)
    {

        $users = new User;
        $users->fullname = $request->fullName;
        $users->email  = $request->email ;
        $users->phone  = $request->phone ;
        $users->country = $request->country;
        $users->volunteerTypeId = $request->volenteerTypeId;
        // $users->password="pas";
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
}
