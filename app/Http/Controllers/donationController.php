<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class donationController extends Controller
{
    public function donationList()
    {
        return Donation::all();
    }
    public function addDonation(Request $request)
    {
        // return $request;
        $donation = new Donation();
        $donation->name= $request->name;
        $donation->phone= $request->phone;
        $donation->email= $request->email;
        $donation->amount= $request->amount; //dd("hello ". $request->amount);
        $result= $donation->save();
        if ($result)
        {
            $fromDatabase = Donation::find($donation->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];
            return response()->json($response);
        }
        else
        {
            return response()->json(["error" => "Failed to add Volunteer"], 500);
        }

    }
}
