<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
         function volunteerList($id=null)
    {
        return $id? Volunteer::find($id): Volunteer::all();
    }
    function addvolunteer(Request $request)
    {
        // return $request;
        $volunteer = new Volunteer;
        $volunteer->name = $request->name;
        $volunteer->description  = $request->description ; //dd("hello ". $volunteer->description);
        $result = $volunteer->save();
        if ($result) {
            $fromDatabase = Volunteer::find($volunteer->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];

            return response()->json($response);
        }
        else {
             return response()->json(["error" => "Failed to add Volunteer"], 500);
            }


}
public function editvolunteer(Request $request, $id)
{
    $validated = $request->validate([
        'description' => 'required|string|max:255',
        'name' => 'required|string|max:255'
    ]);

    // Find the volunteer by ID
    $volunteer = Volunteer::findOrFail($id);

    // Update the volunteer with new data
    $volunteer->description = $validated['description'];
    $volunteer->name = $validated['name'];

    // Save the updated volunteer
    $volunteer->save();

    // Return the updated volunteer
    return response()->json($volunteer);
}
public function deleteVolunteer($id)
{
    // Find the volunteer by ID
    $volunteer = Volunteer::find($id);
    // If volunteer does not exist, return a 404 response
    if (!$volunteer) {
        return response()->json(['message' => 'Volunteer not found'], 404);
    }
    // Delete the volunteer
    $volunteer->delete();

    // Return a success message
    return response()->json(['message' => 'Volunteer deleted successfully']);
}
}
