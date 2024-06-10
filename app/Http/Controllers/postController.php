<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;use Illuminate\Support\Facades\Storage;


class postController extends Controller
{
     function postsList()
    {
        return Post::all();

    }

    function addpost(Request $request)
         {
        $posts = new Post;
        $posts->name = $request->name;
        $posts->post_date  = $request->post_date ;
        $posts->description  = $request->description ;

        // Store the image file
        $imagePath = $request->file('image')->store('public');

        // Save the image file name in the database
        $posts->image = $imagePath;

        // Save the posts details to the database
        $result = $posts->save();

        if ($result) {
            // If saved successfully, retrieve the posts data from the database
            $fromDatabase = Post::find($posts->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];
            return response()->json($response);
        } else {
            // If saving failed, return an error response
            return response()->json(["error" => "Failed to add posts"], 500);
        }

    }
    function editPost(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'id' => 'required|integer|exists:posts,id',
            'name' => 'required|string',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Find the post by ID
        $post = Post::find($validated['id']);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Update the post with new data
        $post->name = $validated['name'];
        $post->description = $validated['description'];

        // Check if an image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($post->image) {
                Storage::delete($post->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('public/images');
            $post->image = $imagePath;
        }

        // Save the updated post
        $post->save();

        // Return the updated post
        return response()->json($post);
    }
    function deletePost($id)
{
    $post = Post::find($id);
    if (!$post) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    if ($post->image) {
        Storage::delete($post->image);
    }

    $post->delete();

    return response()->json(['message' => 'Post deleted successfully']);
}

}
