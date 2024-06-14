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
        // return $request;// dd("hello ". $admin->image);
       $admin = new Admin;
        $admin->fullname = $request->fullname;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->password = bcrypt($request->password); // Encrypt the password
        $admin->role = $request->role;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public');
            $admin->image = $imagePath;
        }

        $result = $admin->save();

        if ($result) {
            $fromDatabase = Admin::find($admin->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];
            return response()->json($response);
        } else {
            return response()->json(["error" => "Failed to add Admin"], 500);
        }

    }

public function editAdmin(Request $request, $id)
{
    // return $id;
    $validated = $request->validate([
        'email' => 'required',
        'fullName' => 'required|max:255',
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
         // $admin = Admin::find($id);
        // if (!$admin) {
        //     return response()->json(['message' => 'Admin not found'], 404);
        // }
        // $admin->fullname = $request->fullname;
        // $admin->email = $request->email;
        // $admin->phone = $request->phone;
        // $admin->role = $request->role;

        // if ($request->hasFile('image')) {
        //     // Delete the old image if it exists
        //     if ($admin->image) {
        //         Storage::delete($admin->image);
        //     }
        //     // Store the new image
        //     $imagePath = $request->file('image')->store('public');
        //     $admin->image = $imagePath;
        // }

        // $result = $admin->save();

        // if ($result) {
        //     // If saved successfully, retrieve the admin data from the database
        //     $fromDatabase = Admin::find($admin->id);
        //     $response = [
        //         "message" => "success",
        //         "data" => $fromDatabase
        //     ];
        //     return response()->json($response);
        // } else {
        //     // If saving failed, return an error response
        //     return response()->json(["error" => "Failed to update Admin"], 500);
        // }

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
