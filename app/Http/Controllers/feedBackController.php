<?php

namespace App\Http\Controllers;

use App\Models\FeedBack;
use Illuminate\Http\Request;

class feedBackController extends Controller
{
        function feedbackList($id = null)
    {
        if($id)
        {
            return FeedBack::find($id);
        }
        else
        {
            return FeedBack::all();
        }
    }
   public function addFeedback(Request $request)
    {
        // return $request;
        // dd("hello ". $request);
        $feedBack = new FeedBack;
        $feedBack->name = $request->name;
        $feedBack->email = $request->email;
        $feedBack->message = $request->message;
        $feedBack->is_seen = 0;


        $result = $feedBack->save();

        if ($result) {
            $fromDataBase = FeedBack::find($feedBack->id);
            $response = [
                "message" => "Success",
                "data" => $fromDataBase
            ];
            return response()->json($response);
        } else {
            return response()->json(["error" => "Failed to add FeedBack"], 404);
        }
    }
    public function editFeedback($id)
    {
        $feedBack = FeedBack::find($id);
        if (!$feedBack) {
        return response()->json(['message' => 'FeedBack not found'], 404);
        }
        $feedBack->is_seen=1;
        $result = $feedBack->save();
        if ($result)
        {
            $fromDataBase = FeedBack::find($id);

            return response()->json($fromDataBase);
        }
        else
        {
            return response()->json(["error" => "Failed to edit FeedBack"], 404);
        }
    }
}
