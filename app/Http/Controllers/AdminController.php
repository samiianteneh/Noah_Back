<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    function adminList()
    {
        return Admin::all();
    }
    function addAdmin(Request $request)
    {
        // return $request;
        $admin = new Admin;
        $admin->fullname = $request->fullname;
        $admin->email  = $request->email ;
        $admin->phone  = $request->phone ;
        $admin->password = $request->password;
        $admin->role = $request->role;
        // Store the image file
        $imagePath = $request->file('image')->store('public');

        // Save the image file name in the database
        $admin->image = $imagePath;
// dd("hello ". $admin->image);

        // Save the admin details to the database
        $result = $admin->save();

        if ($result) {
            // If saved successfully, retrieve the admin data from the database
            $fromDatabase = Admin::find($admin->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];
            return response()->json($response);
        } else {
            // If saving failed, return an error response
            return response()->json(["error" => "Failed to add Admin"], 500);
        }

    }

public function editAdmin(Request $request, $id)
{
    $validated = $request->validate([
        'email' => 'required',
        'fullName' => 'required|max:255',
        // 'imageUrl' => 'required', // Validate 'imageUrl' received from front-end
        'phone' => 'required|max:255',
        'role' => 'required|max:255'
    ]);

    // Find the admin by ID
    $admin = Admin::find($id);
    if (!$admin)
    {
        return response()->json(['message' => 'Admin not found'], 404);
    }
    // return $request;
    $admin->email = $validated['email'];

    $admin->fullName = $validated['fullName'];
    $admin->phone = $validated['phone'];
    $admin->role = $validated['role'];   //dd("hello ". $admin->role);
        $result = $admin->save();

    if ($result) {
            // If saved successfully, retrieve the admin data from the database
            $fromDatabase = Admin::find($admin->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];
            return response()->json($response);
        } else {
            // If saving failed, return an error response
            return response()->json(["error" => "Failed to add Admin"], 500);
        }

}


    function deleteAdmin($id)
    {
        $admin = Admin::find($id);
        if(!$admin)
        {
            return response()->json(['message' => 'Admin not found'], 404);
        }
        $admin->delete();
        return  response()->json(['message' => 'Volunteer deleted successfully']);
    }
}
