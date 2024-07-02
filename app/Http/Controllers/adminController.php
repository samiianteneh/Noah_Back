<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class adminController extends Controller
{
    //
    public function adminList()
    {
        return User::where('isAdmin', true)
                ->select('id', 'fullName', 'email', 'phone', 'role','image','created_at','updated_at') // Specify the columns you want to select
                ->get();
    }
    function addAdmin(Request $request)
    {
        // return $request;
        // dd("hello ". $admin->image);
       $admin = new User();
        $admin->fullname = $request->fullname;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->password = Hash::Make($request->password);
        $admin->role = $request->role;
        $admin->isAdmin = 1;

        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('public');
        //     $admin->image = $imagePath;
        // }
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public');
            $imageName = basename($imagePath);
            $admin->image = $imageName;
        }

        $result = $admin->save();

        if ($result) {
            $fromDatabase = User::find($admin->id);
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
        $admin = User::find($id);
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
            $fromDatabase =  User::select('id', 'fullName', 'email', 'phone', 'role', 'image', 'created_at', 'updated_at')
                                ->find($admin->id);
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
        $admin = User::find($id);
        if(!$admin)
        {
            return response()->json(['message' => 'Admin not found'], 404);
        }
        $admin->delete();
        return  response()->json(['message' => 'Admin deleted successfully']);
    }
}
