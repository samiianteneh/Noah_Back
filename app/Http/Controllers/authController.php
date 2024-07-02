<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    //
 public function authUser(Request $request){

        try {
                if (!Auth::attempt($request->only("email", "password"))) {
                    return response()->json("error");
                }

                $user = User::where("email", $request["email"])->firstOrFail();
                $token = $user->createToken("auth_token")->plainTextToken;



                $data = ["token" => $token, "tokenType" => "Bearer", 'user' => $user];

                // dd($data);
                return response()->json($data);

        } catch (Exception $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }
}
